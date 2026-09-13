<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Mail\OrderCancelledMail;
use App\Mail\OrderConfirmedMail;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentTransaction;
use App\Notifications\OrderStatusNotification;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\OrderService;
use Illuminate\Support\Facades\Mail;

class RazorpayWebhookController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private OrderService $orderService
    ) {}

    /**
     * Handle incoming Razorpay webhook events.
     * Razorpay sends events like payment.captured, payment.failed, refund.processed.
     */
    public function handle(Request $request)
    {
        $payload   = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature', '');

        // Verify webhook signature (if RAZORPAY_WEBHOOK_SECRET is configured in .env)
        if (config('services.razorpay.webhook_secret')) {
            if (!$this->paymentService->verifyWebhookSignature($payload, $signature)) {
                Log::warning('Razorpay webhook: invalid signature', ['ip' => $request->ip()]);

                PaymentTransaction::log([
                    'gateway'           => 'razorpay',
                    'event'             => 'webhook_signature_failed',
                    'status'            => 'failed',
                    'error_description' => 'Webhook signature HMAC verification failed',
                    'payload'           => ['headers' => $request->headers->all()],
                ]);

                return response()->json(['error' => 'Invalid signature'], 403);
            }
        }

        $event = $request->json('event');
        $paymentData = $request->json('payload.payment.entity');
        $refundData  = $request->json('payload.refund.entity');

        Log::info("Razorpay Webhook received: {$event}");

        switch ($event) {
            case 'payment.captured':
            case 'order.paid':
                if ($paymentData) {
                    $this->handlePaymentCaptured($paymentData, $event);
                }
                break;

            case 'payment.failed':
                if ($paymentData) {
                    $this->handlePaymentFailed($paymentData);
                }
                break;

            case 'refund.created':
            case 'refund.processed':
                if ($refundData) {
                    $this->handleRefundProcessed($refundData, $event);
                }
                break;

            case 'refund.failed':
                if ($refundData) {
                    $this->handleRefundFailed($refundData);
                }
                break;

            default:
                Log::info("Unhandled Razorpay webhook event: {$event}");
                break;
        }

        return response()->json(['status' => 'ok']);
    }

    private function handlePaymentCaptured(array $data, string $event): void
    {
        $razorpayOrderId = $data['order_id'] ?? null;
        if (!$razorpayOrderId) return;

        $payment = Payment::where('transaction_id', $razorpayOrderId)->first();
        if (!$payment) return;

        $payment->update([
            'status'              => 'success',
            'razorpay_payment_id' => $data['id'] ?? $payment->razorpay_payment_id,
        ]);

        $order = $payment->order;
        if ($order) {
            $this->orderService->confirmOrder($order);

            // Audit Log: Webhook payment captured
            PaymentTransaction::log([
                'order_id'       => $order->id,
                'user_id'        => $order->user_id,
                'payment_id'     => $payment->id,
                'transaction_id' => $data['id'] ?? $razorpayOrderId,
                'gateway'        => 'razorpay',
                'event'          => 'webhook_' . str_replace('.', '_', $event),
                'amount'         => ($data['amount'] ?? ($order->total * 100)) / 100,
                'method'         => $data['method'] ?? 'online',
                'status'         => 'success',
                'payload'        => $data,
            ]);

            // Dispatch customer confirmation email & notification
            try {
                if ($order->user) {
                    $order->user->notify(new OrderStatusNotification($order));
                }
                $email = $order->shipping_email ?? $order->user?->email;
                if ($email) {
                    Mail::to($email)->send(new OrderConfirmedMail($order));
                }
            } catch (\Exception $e) {
                Log::error("Webhook notification error: " . $e->getMessage());
            }

            Log::info("Webhook: Order #{$order->order_number} confirmed as paid via Razorpay webhook.");
        }
    }

    private function handlePaymentFailed(array $data): void
    {
        $razorpayOrderId = $data['order_id'] ?? null;
        if (!$razorpayOrderId) return;

        $payment = Payment::where('transaction_id', $razorpayOrderId)->first();
        if (!$payment) return;

        $payment->update(['status' => 'failed']);
        if ($payment->order && $payment->order->status === 'pending') {
            $this->orderService->cancelOrder($payment->order, 'Razorpay webhook payment.failed');
        }

        // Audit Log: Webhook payment failed
        PaymentTransaction::log([
            'order_id'          => $payment->order_id,
            'user_id'           => $payment->order?->user_id,
            'payment_id'        => $payment->id,
            'transaction_id'    => $data['id'] ?? $razorpayOrderId,
            'gateway'           => 'razorpay',
            'event'             => 'webhook_payment_failed',
            'amount'            => ($data['amount'] ?? ($payment->amount * 100)) / 100,
            'method'            => $data['method'] ?? null,
            'status'            => 'failed',
            'error_code'        => $data['error_code'] ?? null,
            'error_description' => $data['error_description'] ?? 'Payment failed on gateway',
            'payload'           => $data,
        ]);

        Log::warning("Webhook: Payment failed for transaction {$razorpayOrderId}");
    }

    private function handleRefundProcessed(array $data, string $event): void
    {
        $refundId  = $data['id'] ?? null;
        $paymentId = $data['payment_id'] ?? null;
        if (!$refundId && !$paymentId) return;

        $payment = Payment::where('refund_id', $refundId)
            ->orWhere('razorpay_payment_id', $paymentId)
            ->first();

        if (!$payment) return;

        $payment->update([
            'status'        => 'refunded',
            'refund_id'     => $refundId ?? $payment->refund_id,
            'refund_status' => 'processed',
        ]);

        $order = $payment->order;
        if ($order) {
            $order->update([
                'payment_status' => 'refunded',
            ]);

            // Audit Log: Webhook refund processed
            PaymentTransaction::log([
                'order_id'       => $order->id,
                'user_id'        => $order->user_id,
                'payment_id'     => $payment->id,
                'transaction_id' => $refundId,
                'gateway'        => 'razorpay',
                'event'          => 'webhook_' . str_replace('.', '_', $event),
                'amount'         => ($data['amount'] ?? ($order->total * 100)) / 100,
                'status'         => 'refunded',
                'payload'        => $data,
            ]);

            // Dispatch customer refund notice email & notification
            try {
                if ($order->user) {
                    $order->user->notify(new OrderStatusNotification($order));
                }
                $email = $order->shipping_email ?? $order->user?->email;
                if ($email) {
                    Mail::to($email)->send(new OrderCancelledMail($order));
                }
            } catch (\Exception $e) {
                Log::error("Webhook refund notification error: " . $e->getMessage());
            }

            Log::info("Webhook: Refund {$refundId} processed successfully for Order #{$order->order_number}");
        }
    }

    private function handleRefundFailed(array $data): void
    {
        $refundId = $data['id'] ?? null;
        $paymentId = $data['payment_id'] ?? null;

        $payment = Payment::where('refund_id', $refundId)
            ->orWhere('razorpay_payment_id', $paymentId)
            ->first();

        if ($payment) {
            $payment->update(['refund_status' => 'failed']);

            PaymentTransaction::log([
                'order_id'          => $payment->order_id,
                'user_id'           => $payment->order?->user_id,
                'payment_id'        => $payment->id,
                'transaction_id'    => $refundId,
                'gateway'           => 'razorpay',
                'event'             => 'webhook_refund_failed',
                'status'            => 'failed',
                'error_description' => 'Gateway reported refund failure',
                'payload'           => $data,
            ]);

            Log::error("Webhook: Refund failed for Payment ID {$paymentId}, Refund ID: {$refundId}");
        }
    }
}
