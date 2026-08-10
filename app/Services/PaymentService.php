<?php

namespace App\Services;

use Razorpay\Api\Api;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    private $api;

    public function __construct()
    {
        $keyId = env('RAZORPAY_KEY_ID', 'rzp_test_dummy');
        $keySecret = env('RAZORPAY_KEY_SECRET', 'dummy_secret');

        // Only initialize API if keys aren't dummy placeholders, or we'll wrap calls in try/catch for dummy mode
        if ($keyId !== 'rzp_test_dummy') {
            $this->api = new Api($keyId, $keySecret);
        }
    }

    /**
     * Create Razorpay Order ID for frontend checkout
     */
    public function initializeRazorpayPayment(Order $order)
    {
        if (!$this->api) {
            // Dummy mode for development
            return 'order_' . \Illuminate\Support\Str::random(14);
        }

        try {
            $orderData = [
                'receipt'         => (string) $order->id,
                'amount'          => (int) ($order->total * 100), // Amount in paise
                'currency'        => 'INR',
                'payment_capture' => 1 // auto capture
            ];

            $razorpayOrder = $this->api->order->create($orderData);
            
            return $razorpayOrder['id'];
        } catch (\Exception $e) {
            Log::error("Razorpay Order Creation Failed: " . $e->getMessage());
            throw new \Exception("Failed to initialize payment gateway.");
        }
    }

    /**
     * Verify payment signature from frontend callback
     */
    public function verifyRazorpaySignature($attributes)
    {
        if (!$this->api) {
            // Dummy mode - auto approve
            return true;
        }

        try {
            $this->api->utility->verifyPaymentSignature([
                'razorpay_signature'  => $attributes['razorpay_signature'],
                'razorpay_payment_id' => $attributes['razorpay_payment_id'],
                'razorpay_order_id'   => $attributes['razorpay_order_id']
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error("Razorpay Signature Verification Failed: " . $e->getMessage());
            return false;
        }
    }
}
