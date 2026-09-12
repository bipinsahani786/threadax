<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\CheckoutRequest;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentService;
use App\Services\CouponService;
use App\Mail\OrderConfirmedMail;
use App\Models\Address;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentTransaction;
use App\Notifications\OrderStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService    $cartService,
        private OrderService   $orderService,
        private PaymentService $paymentService,
        private CouponService  $couponService
    ) {}

    public function index()
    {
        $cart      = $this->cartService->getCart();
        $summary   = $this->cartService->getSummary();
        $addresses = Auth::check() ? Auth::user()->addresses : collect();

        // Rehydrate any coupon stored in session
        $coupon   = null;
        $discount = 0;
        if (session('coupon_code')) {
            $result = $this->couponService->validate(session('coupon_code'), $summary['subtotal']);
            if ($result['valid']) {
                $coupon   = $result['coupon'];
                $discount = $result['discount'];
            } else {
                session()->forget(['coupon_code', 'coupon_discount']);
            }
        }

        $razorpayKeyId = $this->paymentService->getKeyId();

        // Recommendations (Random 4 active products)
        $recommendedProducts = \App\Models\Product::with('images', 'variants')
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Available Coupons
        $availableCoupons = \App\Models\Coupon::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->where(function ($query) {
                $query->whereNull('starts_at')
                      ->orWhere('starts_at', '<=', now());
            })
            ->get()
            ->filter(function ($coupon) {
                return $coupon->isValid(); // Double check logic
            });

        // Wishlist Items
        $wishlistItems = Auth::check() ? Auth::user()->wishlists()->whereHas('product')->with('product.variants', 'product.images')->get() : collect();

        return view('frontend.pages.checkout.index', compact(
            'cart', 'summary', 'addresses', 'coupon', 'discount', 'razorpayKeyId', 'recommendedProducts', 'availableCoupons', 'wishlistItems'
        ));
    }

    /**
     * AJAX: Apply a coupon code.
     */
    public function applyCoupon(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string|max:50']);

        $summary = $this->cartService->getSummary();
        $result  = $this->couponService->validate($request->coupon_code, $summary['subtotal']);

        if (! $result['valid']) {
            return response()->json(['success' => false, 'message' => $result['message']], 422);
        }

        session([
            'coupon_code'     => strtoupper($request->coupon_code),
            'coupon_discount' => $result['discount'],
        ]);

        $newTotal = max(0, $summary['total'] - $result['discount']);

        return response()->json([
            'success'  => true,
            'message'  => 'Coupon applied! You save ₹' . number_format($result['discount'], 2),
            'discount' => number_format($result['discount'], 2),
            'total'    => number_format($newTotal, 2),
            'coupon'   => $result['coupon']->code,
        ]);
    }

    /**
     * AJAX: Remove applied coupon.
     */
    public function removeCoupon()
    {
        session()->forget(['coupon_code', 'coupon_discount']);
        $summary = $this->cartService->getSummary();

        return response()->json([
            'success' => true,
            'total'   => number_format($summary['total'], 2),
        ]);
    }

    /**
     * Process checkout: validate address, create order, init payment.
     */
    public function process(CheckoutRequest $request)
    {
        $cart = $this->cartService->getCart();
        if ($cart->items->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty.'], 422);
        }

        try {
            $user = Auth::user();

            // Handle Address
            if ($request->address_id) {
                // Ensure the address belongs to this user (authorization check)
                $address = $user->addresses()->findOrFail($request->address_id);
                $addressId = $address->id;
            } else {
                // Determine if this should be default
                $isDefault = $user->addresses()->count() === 0;

                $address = Address::create([
                    'user_id'         => $user->id,
                    'name'            => $request->name,
                    'phone'           => $request->phone,
                    'alternate_phone' => $request->alternate_phone,
                    'line1'           => $request->line1,
                    'line2'           => $request->line2,
                    'landmark'        => $request->landmark,
                    'city'            => $request->city,
                    'state'           => $request->state,
                    'pincode'         => $request->pincode,
                    'type'            => 'home', // default
                    'is_default'      => $isDefault,
                ]);
                $addressId = $address->id;
            }

            // Resolve coupon from session
            $coupon   = null;
            $discount = 0;
            if (session('coupon_code')) {
                $summary = $this->cartService->getSummary();
                $result  = $this->couponService->validate(session('coupon_code'), $summary['subtotal']);
                if ($result['valid']) {
                    $coupon   = $result['coupon'];
                    $discount = $result['discount'];
                }
            }

            // Create Order
            $order = $this->orderService->createOrder($user->id, $addressId, $request->payment_method, $coupon, $discount);

            // Record coupon usage
            if ($coupon) {
                $this->couponService->recordUsage($coupon, $order->id, $discount);
                session()->forget(['coupon_code', 'coupon_discount']);
            }

            if ($request->payment_method === 'razorpay') {
                $razorpayOrderId = $this->paymentService->initializeRazorpayPayment($order);

                $payment = Payment::create([
                    'order_id'       => $order->id,
                    'transaction_id' => $razorpayOrderId,
                    'amount'         => $order->total,
                    'status'         => 'pending',
                    'payment_method' => 'razorpay',
                ]);

                // Audit Log: Order initialized
                PaymentTransaction::log([
                    'order_id'       => $order->id,
                    'user_id'        => $user->id,
                    'payment_id'     => $payment->id,
                    'transaction_id' => $razorpayOrderId,
                    'gateway'        => 'razorpay',
                    'event'          => 'order_initialized',
                    'amount'         => $order->total,
                    'status'         => 'pending',
                    'payload'        => ['razorpay_order_id' => $razorpayOrderId],
                ]);

                $razorpayKey = config('services.razorpay.key_id') ?: env('RAZORPAY_KEY_ID', env('RAZORPAY_KEY'));

                $razorpayData = [
                    'key'               => $razorpayKey,
                    'razorpay_order_id' => $razorpayOrderId,
                    'amount'            => (int) ($order->total * 100),
                    'currency'          => 'INR',
                    'name'              => 'ThreadAX Streetwear',
                    'description'       => 'Order #' . $order->order_number,
                    'prefill'           => [
                        'name'    => $user->name,
                        'email'   => $user->email,
                        'contact' => $order->address->phone ?? '',
                    ],
                    'order_id_db' => $order->id,
                ];

                return response()->json(array_merge([
                    'success'  => true,
                    'razorpay' => $razorpayData,
                ], $razorpayData));
            }

            // COD flow
            $order->update(['payment_status' => 'pending', 'status' => 'processing']);

            // Audit Log: COD order
            PaymentTransaction::log([
                'order_id'       => $order->id,
                'user_id'        => $user->id,
                'transaction_id' => 'COD-' . $order->order_number,
                'gateway'        => 'cod',
                'method'         => 'cod',
                'event'          => 'cod_order_placed',
                'amount'         => $order->total,
                'status'         => 'pending',
            ]);

            // Dispatch customer order confirmation notification & email
            try {
                $user->notify(new OrderStatusNotification($order));
                $email = $order->shipping_email ?? $user->email;
                if ($email) {
                    Mail::to($email)->send(new OrderConfirmedMail($order));
                }
            } catch (\Exception $ne) {
                Log::error("Failed to dispatch order confirmation notification: " . $ne->getMessage());
            }

            $redirectUrl = route('frontend.checkout.success', $order->id);

            return response()->json([
                'success'      => true,
                'redirect'     => $redirectUrl,
                'redirect_url' => $redirectUrl,
            ]);

        } catch (\Exception $e) {
            Log::error('Checkout process failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
            ]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Razorpay payment callback — verify signature and confirm order.
     */
    public function callback(Request $request)
    {
        $request->validate([
            'razorpay_signature'  => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id'   => 'required|string',
            'order_id_db'         => 'nullable|integer',
        ]);

        $attributes = [
            'razorpay_signature'  => $request->razorpay_signature,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_order_id'   => $request->razorpay_order_id,
        ];

        if ($this->paymentService->verifyRazorpaySignature($attributes)) {
            $payment = Payment::where('transaction_id', $request->razorpay_order_id)
                               ->where('status', 'pending')
                               ->first();

            if ($payment) {
                $payment->update([
                    'status'              => 'success',
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'transaction_id'      => $request->razorpay_order_id,
                ]);

                $confirmedOrder = $payment->order;
                $confirmedOrder->update([
                    'payment_status' => 'paid',
                    'status'         => 'processing',
                ]);

                // Dispatch customer order confirmation notification & email
                try {
                    if ($confirmedOrder->user) {
                        $confirmedOrder->user->notify(new OrderStatusNotification($confirmedOrder));
                    }
                    $email = $confirmedOrder->shipping_email ?? $confirmedOrder->user?->email;
                    if ($email) {
                        Mail::to($email)->send(new OrderConfirmedMail($confirmedOrder));
                    }
                } catch (\Exception $ne) {
                    Log::error("Failed to dispatch razorpay order confirmation notification: " . $ne->getMessage());
                }

                // Audit Log: Payment success
                PaymentTransaction::log([
                    'order_id'       => $confirmedOrder->id,
                    'user_id'        => $confirmedOrder->user_id,
                    'payment_id'     => $payment->id,
                    'transaction_id' => $request->razorpay_payment_id,
                    'gateway'        => 'razorpay',
                    'event'          => 'payment_success',
                    'amount'         => $confirmedOrder->total,
                    'status'         => 'success',
                    'payload'        => $attributes,
                ]);

                return redirect()->route('frontend.checkout.success', $payment->order_id);
            }
        }

        // Audit Log: Payment verification failure
        PaymentTransaction::log([
            'transaction_id'    => $request->razorpay_payment_id ?? $request->razorpay_order_id,
            'gateway'           => 'razorpay',
            'event'             => 'payment_failed',
            'amount'            => 0,
            'status'            => 'failed',
            'error_description' => 'Cryptographic signature verification failed',
            'payload'           => $attributes,
        ]);

        Log::warning('Razorpay callback verification failed', [
            'razorpay_order_id'   => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
        ]);

        return redirect()->route('frontend.checkout.index')
                         ->with('error', 'Payment verification failed. Please contact support if money was deducted.');
    }

    /**
     * Order success page.
     */
    public function success($orderId)
    {
        $order = Order::with(['items.variant.product.images', 'address'])
                      ->where('id', $orderId)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

        return view('frontend.pages.checkout.success', compact('order'));
    }
}
