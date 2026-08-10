<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Models\Address;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private OrderService $orderService,
        private PaymentService $paymentService
    ) {}

    public function index()
    {
        $cart = $this->cartService->getCart();
        
        if ($cart->items->isEmpty()) {
            return redirect()->route('frontend.products.index')->with('error', 'Your cart is empty.');
        }

        $summary = $this->cartService->getSummary();
        $addresses = Auth::user()->addresses; // User is authenticated due to middleware

        return view('frontend.pages.checkout.index', compact('cart', 'summary', 'addresses'));
    }

    public function process(Request $request)
    {
        $cart = $this->cartService->getCart();
        if ($cart->items->isEmpty()) {
            return redirect()->route('frontend.products.index');
        }

        $request->validate([
            'address_id' => 'nullable|exists:addresses,id',
            'name' => 'required_without:address_id|string|max:255',
            'phone' => 'required_without:address_id|string|max:20',
            'street' => 'required_without:address_id|string|max:255',
            'city' => 'required_without:address_id|string|max:255',
            'state' => 'required_without:address_id|string|max:255',
            'pincode' => 'required_without:address_id|string|max:10',
            'payment_method' => 'required|in:cod,razorpay'
        ]);

        try {
            $user = Auth::user();
            
            // Handle Address
            if ($request->address_id) {
                $addressId = $request->address_id;
            } else {
                $address = Address::create([
                    'user_id' => $user->id,
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'street' => $request->street,
                    'city' => $request->city,
                    'state' => $request->state,
                    'pincode' => $request->pincode,
                    'type' => 'home'
                ]);
                $addressId = $address->id;
            }

            // Create Order
            $order = $this->orderService->createOrder($user->id, $addressId, $request->payment_method);

            if ($request->payment_method === 'razorpay') {
                $razorpayOrderId = $this->paymentService->initializeRazorpayPayment($order);
                
                // Save payment init record
                Payment::create([
                    'order_id' => $order->id,
                    'transaction_id' => $razorpayOrderId,
                    'amount' => $order->total,
                    'status' => 'pending',
                    'payment_method' => 'razorpay'
                ]);

                return response()->json([
                    'success' => true,
                    'razorpay_order_id' => $razorpayOrderId,
                    'amount' => $order->total * 100, // in paise
                    'currency' => 'INR',
                    'name' => 'ThreadAx',
                    'description' => 'Order #' . $order->order_number,
                    'prefill' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'contact' => $order->address->phone ?? ''
                    ],
                    'order_id_db' => $order->id
                ]);
            } else {
                // COD
                return response()->json([
                    'success' => true,
                    'redirect_url' => route('frontend.checkout.success', $order->id)
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function callback(Request $request)
    {
        $attributes = [
            'razorpay_signature' => $request->razorpay_signature,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_order_id' => $request->razorpay_order_id
        ];

        if ($this->paymentService->verifyRazorpaySignature($attributes)) {
            // Success
            $payment = Payment::where('transaction_id', $request->razorpay_order_id)->first();
            if ($payment) {
                $payment->update([
                    'status' => 'success',
                    'transaction_id' => $request->razorpay_payment_id // update to actual payment id
                ]);
                $payment->order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing'
                ]);
                return redirect()->route('frontend.checkout.success', $payment->order_id);
            }
        }

        // Failure
        // In real app, you might want to redirect to a failure page or update payment status to failed
        return redirect()->route('frontend.checkout.index')->with('error', 'Payment failed or was cancelled.');
    }

    public function success($orderId)
    {
        $order = Order::where('id', $orderId)->where('user_id', Auth::id())->firstOrFail();
        
        return view('frontend.pages.checkout.success', compact('order'));
    }
}
