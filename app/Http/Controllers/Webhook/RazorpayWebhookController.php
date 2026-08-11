<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RazorpayWebhookController extends Controller
{
    public function __construct(private PaymentService $paymentService) {}

    /**
     * Handle incoming Razorpay webhook events.
     * Razorpay sends events like payment.captured, payment.failed, refund.processed.
     */
    public function handle(Request $request)
    {
        $payload   = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature', '');

        // Verify webhook signature (requires RAZORPAY_WEBHOOK_SECRET in .env)
        if (! $this->paymentService->verifyWebhookSignature($payload, $signature)) {
            Log::warning('Razorpay webhook: invalid signature', ['ip' => $request->ip()]);
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $event = $request->json('event');
        $data  = $request->json('payload.payment.entity') ?? $request->json('payload.refund.entity');

        Log::info("Razorpay Webhook received: {$event}");

        match ($event) {
            'payment.captured'   => $this->handlePaymentCaptured($data),
            'payment.failed'     => $this->handlePaymentFailed($data),
            'refund.processed'   => $this->handleRefundProcessed($request->json('payload.refund.entity')),
            default              => Log::info("Unhandled Razorpay event: {$event}"),
        };

        return response()->json(['status' => 'ok']);
    }

    private function handlePaymentCaptured(array $data): void
    {
        $razorpayOrderId = $data['order_id'] ?? null;
        if (! $razorpayOrderId) return;

        $payment = Payment::where('transaction_id', $razorpayOrderId)->first();
        if (! $payment || $payment->status === 'success') return;

        $payment->update([
            'status'              => 'success',
            'razorpay_payment_id' => $data['id'],
        ]);

        $payment->order->update([
            'payment_status' => 'paid',
            'status'         => 'processing',
        ]);

        Log::info('Webhook: payment confirmed via webhook', ['order_id' => $payment->order_id]);
    }

    private function handlePaymentFailed(array $data): void
    {
        $razorpayOrderId = $data['order_id'] ?? null;
        if (! $razorpayOrderId) return;

        $payment = Payment::where('transaction_id', $razorpayOrderId)->first();
        if (! $payment) return;

        $payment->update(['status' => 'failed']);
        $payment->order->update(['payment_status' => 'failed', 'status' => 'cancelled']);

        Log::warning('Webhook: payment failed', ['order_id' => $payment->order_id]);
    }

    private function handleRefundProcessed(array $data): void
    {
        $refundId  = $data['id'] ?? null;
        if (! $refundId) return;

        $payment = Payment::where('refund_id', $refundId)->first();
        if (! $payment) return;

        $payment->update(['refund_status' => 'processed']);
        $payment->order->update(['payment_status' => 'refunded']);

        Log::info('Webhook: refund processed', ['refund_id' => $refundId]);
    }
}
