<?php

namespace App\Services;

use Razorpay\Api\Api;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    private ?Api $api;

    public function __construct()
    {
        $keyId     = config('services.razorpay.key_id');
        $keySecret = config('services.razorpay.key_secret');

        if ($keyId && $keySecret) {
            $this->api = new Api($keyId, $keySecret);
        } else {
            $this->api = null;
        }
    }

    /**
     * Create Razorpay Order ID for frontend checkout.
     * Throws an exception if keys are not configured.
     */
    public function initializeRazorpayPayment(Order $order): string
    {
        if (! $this->api) {
            throw new \RuntimeException(
                'Razorpay is not configured. Please set RAZORPAY_KEY_ID and RAZORPAY_KEY_SECRET in your .env file.'
            );
        }

        try {
            $razorpayOrder = $this->api->order->create([
                'receipt'         => (string) $order->id,
                'amount'          => (int) ($order->total * 100), // in paise
                'currency'        => 'INR',
                'payment_capture' => 1, // auto-capture
            ]);

            return $razorpayOrder['id'];
        } catch (\Exception $e) {
            Log::error('Razorpay Order Creation Failed: ' . $e->getMessage(), [
                'order_id' => $order->id,
            ]);
            throw new \RuntimeException('Failed to initialize payment gateway. Please try again.');
        }
    }

    /**
     * Verify the HMAC-SHA256 payment signature from Razorpay callback.
     */
    public function verifyRazorpaySignature(array $attributes): bool
    {
        if (! $this->api) {
            Log::warning('Razorpay signature verification skipped — API not configured.');
            return false;
        }

        try {
            $this->api->utility->verifyPaymentSignature([
                'razorpay_signature'  => $attributes['razorpay_signature'],
                'razorpay_payment_id' => $attributes['razorpay_payment_id'],
                'razorpay_order_id'   => $attributes['razorpay_order_id'],
            ]);
            return true;
        } catch (\Exception $e) {
            Log::warning('Razorpay Signature Verification Failed: ' . $e->getMessage(), [
                'payment_id' => $attributes['razorpay_payment_id'] ?? 'unknown',
            ]);
            return false;
        }
    }

    /**
     * Verify Razorpay webhook signature.
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $webhookSecret = config('services.razorpay.webhook_secret');
        if (! $webhookSecret) {
            return false;
        }
        $expected = hash_hmac('sha256', $payload, $webhookSecret);
        return hash_equals($expected, $signature);
    }

    /**
     * Initiate a full or partial refund for a Razorpay payment.
     * Returns the refund ID on success.
     */
    public function initiateRefund(Payment $payment, ?int $amountInPaise = null): string
    {
        if (! $this->api) {
            throw new \RuntimeException(
                'Razorpay is not configured. Cannot process refund automatically.'
            );
        }

        if (! $payment->razorpay_payment_id) {
            throw new \RuntimeException(
                'No Razorpay Payment ID found on this payment record. Refund cannot be processed automatically.'
            );
        }

        try {
            $params = ['speed' => 'optimum']; // 'normal' = bank transfer, 'optimum' = instant if possible
            if ($amountInPaise !== null) {
                $params['amount'] = $amountInPaise;
            }

            $refund = $this->api->payment->fetch($payment->razorpay_payment_id)->refund($params);

            Log::info('Razorpay Refund Initiated', [
                'payment_id' => $payment->razorpay_payment_id,
                'refund_id'  => $refund['id'],
                'amount'     => $refund['amount'],
            ]);

            return $refund['id'];
        } catch (\Exception $e) {
            Log::error('Razorpay Refund Failed: ' . $e->getMessage(), [
                'payment_id' => $payment->razorpay_payment_id,
            ]);
            throw new \RuntimeException('Refund processing failed: ' . $e->getMessage());
        }
    }

    /**
     * Get the Razorpay Key ID for frontend use.
     */
    public function getKeyId(): ?string
    {
        return config('services.razorpay.key_id');
    }
}
