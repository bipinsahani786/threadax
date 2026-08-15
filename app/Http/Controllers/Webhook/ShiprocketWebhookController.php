<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Mail\OrderDeliveredMail;
use App\Models\Order;
use App\Models\OrderTracking;
use App\Services\ShiprocketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ShiprocketWebhookController extends Controller
{
    public function __construct(private ShiprocketService $shiprocketService) {}

    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();
        Log::info('Shiprocket Webhook Received', ['payload' => $payload]);

        $orderId = $payload['order_id'] ?? ($payload['channel_order_id'] ?? null);
        $awb = $payload['awb'] ?? ($payload['awb_code'] ?? null);
        $currentStatus = $payload['current_status'] ?? ($payload['status'] ?? null);

        if (!$orderId && !$awb) {
            return response()->json(['status' => 'ignored', 'message' => 'Missing order ID and AWB'], 400);
        }

        // Find Order
        $order = Order::where('order_number', $orderId)
            ->orWhere('shiprocket_order_id', $orderId)
            ->orWhere('shiprocket_awb_code', $awb)
            ->orWhere('tracking_number', $awb)
            ->first();

        if (!$order) {
            Log::warning('Shiprocket Webhook: Order not found', ['order_id' => $orderId, 'awb' => $awb]);
            return response()->json(['status' => 'not_found', 'message' => 'Order not found'], 404);
        }

        $mappedStatus = $currentStatus ? $this->shiprocketService->mapShiprocketStatus($currentStatus) : 'processing';
        $location = $payload['location'] ?? ($payload['city'] ?? 'In Transit Hub');
        $activity = $payload['activity'] ?? ($payload['scans'][0]['activity'] ?? "Shiprocket status: {$currentStatus}");
        $courier = $payload['courier_name'] ?? $order->effective_courier;

        // Record tracking checkpoint
        $order->trackings()->create([
            'status'          => $mappedStatus,
            'title'           => $activity,
            'location'        => $location,
            'activity'        => $payload['activity_detail'] ?? $activity,
            'courier_name'    => $courier,
            'tracking_number' => $awb ?: $order->effective_awb,
            'event_time'      => isset($payload['current_timestamp']) ? \Carbon\Carbon::parse($payload['current_timestamp']) : now(),
        ]);

        $oldStatus = $order->status;
        $orderUpdate = ['status' => $mappedStatus];

        if ($awb && !$order->shiprocket_awb_code) {
            $orderUpdate['shiprocket_awb_code'] = $awb;
            $orderUpdate['tracking_number'] = $awb;
        }
        if ($courier) {
            $orderUpdate['shiprocket_courier_name'] = $courier;
            $orderUpdate['courier_name'] = $courier;
        }

        if ($mappedStatus === 'delivered' && $oldStatus !== 'delivered') {
            $orderUpdate['delivered_at'] = now();
            if ($order->payment_method === 'cod') {
                $orderUpdate['payment_status'] = 'paid';
            }
        }

        $order->update($orderUpdate);

        // If delivered, send invoice email
        if ($mappedStatus === 'delivered' && $oldStatus !== 'delivered') {
            try {
                $recipientEmail = $order->shipping_email ?? $order->user?->email;
                if ($recipientEmail) {
                    Mail::to($recipientEmail)->send(new OrderDeliveredMail($order));
                    Log::info("Webhook: Order delivered email with invoice sent for order #{$order->order_number}");
                }
            } catch (\Exception $e) {
                Log::error("Webhook: Failed sending delivered email: " . $e->getMessage());
            }
        }

        return response()->json([
            'status'  => 'success',
            'message' => "Order #{$order->order_number} tracking updated to {$mappedStatus}",
        ]);
    }
}
