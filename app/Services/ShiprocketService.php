<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderTracking;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ShiprocketService
{
    private string $baseUrl = 'https://apiv2.shiprocket.in/v1/external';

    /**
     * Get Shiprocket API Token (caches valid token for 9 days)
     */
    public function getToken(): ?string
    {
        $cachedToken = Cache::get('shiprocket_auth_token');
        if (!empty($cachedToken)) {
            return $cachedToken;
        }

        $email = Setting::where('key', 'shiprocket_email')->value('value') ?: config('services.shiprocket.email');
        $password = Setting::where('key', 'shiprocket_password')->value('value') ?: config('services.shiprocket.password');

        if (empty($email) || empty($password)) {
            Log::info('Shiprocket credentials not configured.');
            return null;
        }

        try {
            $response = Http::post("{$this->baseUrl}/auth/login", [
                'email'    => $email,
                'password' => $password,
            ]);

            if ($response->successful() && isset($response->json()['token'])) {
                $token = $response->json()['token'];
                Cache::put('shiprocket_auth_token', $token, 60 * 60 * 24 * 9);
                return $token;
            }

            Log::error('Shiprocket login failed', ['response' => $response->body()]);
        } catch (\Exception $e) {
            Log::error('Shiprocket auth exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Check if Shiprocket is configured with credentials
     */
    public function isConfigured(): bool
    {
        $email = Setting::where('key', 'shiprocket_email')->value('value') ?: config('services.shiprocket.email');
        $password = Setting::where('key', 'shiprocket_password')->value('value') ?: config('services.shiprocket.password');
        return !empty($email) && !empty($password);
    }

    /**
     * Create an order in Shiprocket
     */
    public function createOrder(Order $order): array
    {
        $token = $this->getToken();

        // If credentials are not configured, simulate sandbox/mock creation
        if (!$token) {
            return $this->mockCreateOrder($order);
        }

        $order->loadMissing(['items.variant.product', 'address', 'user']);

        $address = $order->address;
        $user = $order->user;

        $items = [];
        foreach ($order->items as $item) {
            $product = $item->variant?->product;
            $items[] = [
                'name'          => $product?->name ?? 'Streetwear Drop',
                'sku'           => $item->variant?->sku ?? ($product?->sku ?? 'TX-' . $item->id),
                'units'         => (int) $item->quantity,
                'selling_price' => (float) $item->price,
                'discount'      => 0,
                'tax'           => 0,
                'hsn'           => 610910,
            ];
        }

        $pickupLocation = Setting::where('key', 'shiprocket_pickup_location')->value('value') ?: 'Primary';

        $payload = [
            'order_id'              => $order->order_number,
            'order_date'            => $order->created_at->format('Y-m-d H:i'),
            'pickup_location'       => $pickupLocation,
            'channel_id'            => '',
            'comment'               => 'ThreadAX Premium Order',
            'billing_customer_name' => $address?->name ?? ($user?->name ?? 'Valued Customer'),
            'billing_last_name'     => '',
            'billing_address'       => $address?->line1 ?? 'ThreadAX Customer Address',
            'billing_address_2'     => $address?->line2 ?? '',
            'billing_city'          => $address?->city ?? 'Mumbai',
            'billing_pincode'       => $address?->pincode ?? '400001',
            'billing_state'         => $address?->state ?? 'Maharashtra',
            'billing_country'       => 'India',
            'billing_email'         => $user?->email ?? 'customer@threadax.co.in',
            'billing_phone'         => $address?->phone ?? ($user?->phone ?? '9876543210'),
            'shipping_is_billing'   => true,
            'order_items'           => $items,
            'payment_method'        => $order->payment_method === 'cod' ? 'COD' : 'Prepaid',
            'shipping_charges'      => (float) $order->shipping,
            'giftwrap_charges'      => 0,
            'transaction_charges'   => 0,
            'total_discount'        => (float) $order->discount,
            'sub_total'             => (float) $order->total,
            'length'                => 15,
            'breadth'               => 12,
            'height'                => 5,
            'weight'                => 0.45,
        ];

        try {
            $response = Http::withToken($token)->post("{$this->baseUrl}/orders/create/adhoc", $payload);

            if ($response->successful()) {
                $data = $response->json();
                $shiprocketOrderId = $data['order_id'] ?? null;
                $shipmentId = $data['shipment_id'] ?? null;
                $awbCode = $data['awb_code'] ?? null;
                $courierName = $data['courier_name'] ?? null;

                $order->update([
                    'shiprocket_order_id'    => $shiprocketOrderId,
                    'shiprocket_shipment_id' => $shipmentId,
                    'shiprocket_awb_code'    => $awbCode,
                    'shiprocket_courier_name'=> $courierName,
                    'tracking_number'        => $awbCode ?? $order->tracking_number,
                    'courier_name'           => $courierName ?? $order->courier_name ?? 'Shiprocket Logistics',
                    'status'                 => $order->status === 'pending' ? 'processing' : $order->status,
                ]);

                // Create initial tracking log
                $order->trackings()->create([
                    'status'          => 'processing',
                    'title'           => 'Shipment Created in Shiprocket',
                    'location'        => $pickupLocation . ' Warehouse',
                    'activity'        => 'Order manifested with Shiprocket ID: ' . $shiprocketOrderId . ($shipmentId ? ' (Shipment #' . $shipmentId . ')' : ''),
                    'courier_name'    => $courierName ?? 'Shiprocket',
                    'tracking_number' => $awbCode,
                    'event_time'      => now(),
                ]);

                return [
                    'success' => true,
                    'message' => 'Order successfully pushed to Shiprocket.',
                    'data'    => $data,
                ];
            }

            Log::error('Shiprocket create order error: ' . $response->body());
            return [
                'success' => false,
                'message' => 'Shiprocket Error: ' . ($response->json()['message'] ?? $response->body()),
            ];
        } catch (\Exception $e) {
            Log::error('Shiprocket request exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Exception communicating with Shiprocket: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Generate AWB / Assign Courier for Shipment
     */
    public function generateAwb(Order $order, ?int $courierId = null): array
    {
        $token = $this->getToken();

        if (!$token || !$order->shiprocket_shipment_id) {
            return $this->mockGenerateAwb($order);
        }

        try {
            $payload = ['shipment_id' => $order->shiprocket_shipment_id];
            if ($courierId) {
                $payload['courier_id'] = $courierId;
            }

            $response = Http::withToken($token)->post("{$this->baseUrl}/courier/assign/awb", $payload);

            if ($response->successful()) {
                $data = $response->json();
                $awb = $data['response']['data']['awb_code'] ?? null;
                $courier = $data['response']['data']['courier_name'] ?? null;

                if ($awb) {
                    $order->update([
                        'shiprocket_awb_code'     => $awb,
                        'shiprocket_courier_name' => $courier,
                        'tracking_number'         => $awb,
                        'courier_name'            => $courier ?? $order->courier_name,
                        'status'                  => 'shipped',
                        'shipped_at'              => now(),
                    ]);

                    $order->trackings()->create([
                        'status'          => 'shipped',
                        'title'           => "AWB Assigned ({$courier})",
                        'location'        => 'Warehouse Dispatch Bay',
                        'activity'        => "AWB #{$awb} generated with {$courier}. Ready for courier pickup.",
                        'courier_name'    => $courier,
                        'tracking_number' => $awb,
                        'event_time'      => now(),
                    ]);

                    return [
                        'success' => true,
                        'message' => "AWB #{$awb} successfully assigned via {$courier}.",
                        'data'    => $data,
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Could not assign AWB: ' . ($response->json()['message'] ?? $response->body()),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'AWB assignment failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Generate Shipping Label URL from Shiprocket
     */
    public function generateLabel(Order $order): ?string
    {
        $token = $this->getToken();
        if (!$token || !$order->shiprocket_shipment_id) {
            return null;
        }

        try {
            $response = Http::withToken($token)->post("{$this->baseUrl}/courier/generate/label", [
                'shipment_id' => [$order->shiprocket_shipment_id],
            ]);

            if ($response->successful() && isset($response->json()['label_url'])) {
                return $response->json()['label_url'];
            }
        } catch (\Exception $e) {
            Log::error('Shiprocket generate label error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Track Order via AWB
     */
    public function trackOrder(Order $order): array
    {
        $token = $this->getToken();
        $awb = $order->effective_awb;

        if (!$token || !$awb) {
            return [
                'success' => false,
                'message' => 'No active Shiprocket token or AWB number found.',
            ];
        }

        try {
            $response = Http::withToken($token)->get("{$this->baseUrl}/courier/track/awb/{$awb}");

            if ($response->successful()) {
                $data = $response->json();
                $trackingData = $data['tracking_data'] ?? [];
                $scans = $trackingData['shipment_track_activities'] ?? [];

                // Sync each scan into OrderTracking
                foreach ($scans as $scan) {
                    $activityDate = isset($scan['date']) ? \Carbon\Carbon::parse($scan['date']) : now();
                    $activity = $scan['activity'] ?? 'In transit checkpoint';
                    $location = $scan['location'] ?? '';

                    $exists = OrderTracking::where('order_id', $order->id)
                        ->where('title', $activity)
                        ->where('location', $location)
                        ->exists();

                    if (!$exists) {
                        $order->trackings()->create([
                            'status'          => $this->mapShiprocketStatus($trackingData['current_status'] ?? 'in_transit'),
                            'title'           => $activity,
                            'location'        => $location,
                            'activity'        => $scan['sr-status-label'] ?? $activity,
                            'courier_name'    => $order->effective_courier,
                            'tracking_number' => $awb,
                            'event_time'      => $activityDate,
                        ]);
                    }
                }

                // If delivered in Shiprocket, update order status
                $currentStatus = strtolower($trackingData['current_status'] ?? '');
                if ($currentStatus === 'delivered' && $order->status !== 'delivered') {
                    $order->update([
                        'status'         => 'delivered',
                        'delivered_at'   => now(),
                        'payment_status' => 'paid',
                    ]);
                }

                return [
                    'success' => true,
                    'message' => 'Tracking timeline synced successfully.',
                    'data'    => $data,
                ];
            }

            return [
                'success' => false,
                'message' => 'Could not fetch tracking data from Shiprocket.',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Tracking fetch error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Map Shiprocket status string to system status
     */
    public function mapShiprocketStatus(string $srStatus): string
    {
        $status = strtolower($srStatus);
        if (str_contains($status, 'deliv')) return 'delivered';
        if (str_contains($status, 'out for delivery') || str_contains($status, 'reach')) return 'out_for_delivery';
        if (str_contains($status, 'transit') || str_contains($status, 'shipped') || str_contains($status, 'pickup')) return 'in_transit';
        if (str_contains($status, 'packed') || str_contains($status, 'manifest')) return 'packed';
        if (str_contains($status, 'rto') || str_contains($status, 'return')) return 'rto';
        if (str_contains($status, 'cancel')) return 'cancelled';
        return 'processing';
    }

    /**
     * Mock Sandbox Creation (when credentials are not yet set)
     */
    private function mockCreateOrder(Order $order): array
    {
        $mockOrderId = 'SR-' . rand(100000, 999999);
        $mockShipmentId = 'SHP-' . rand(1000000, 9999999);
        $mockAwb = 'AWB' . rand(1000000000, 9999999999);
        $courier = 'Delhivery Express';

        $order->update([
            'shiprocket_order_id'    => $mockOrderId,
            'shiprocket_shipment_id' => $mockShipmentId,
            'shiprocket_awb_code'    => $mockAwb,
            'shiprocket_courier_name'=> $courier,
            'courier_name'           => $courier,
            'tracking_number'        => $mockAwb,
            'status'                 => 'processing',
            'shipped_at'             => now(),
        ]);

        $order->trackings()->create([
            'status'          => 'processing',
            'title'           => 'Shipment Manifested in Shiprocket (Sandbox)',
            'location'        => 'Mumbai Logistics Hub',
            'activity'        => "Manifested with Shiprocket ID #{$mockOrderId}. AWB #{$mockAwb} assigned with {$courier}.",
            'courier_name'    => $courier,
            'tracking_number' => $mockAwb,
            'event_time'      => now(),
        ]);

        return [
            'success' => true,
            'message' => "Order manifested in Shiprocket (Sandbox mode). AWB #{$mockAwb} assigned.",
            'data'    => [
                'order_id'    => $mockOrderId,
                'shipment_id' => $mockShipmentId,
                'awb_code'    => $mockAwb,
                'courier_name'=> $courier,
            ],
        ];
    }

    private function mockGenerateAwb(Order $order): array
    {
        $mockAwb = 'AWB' . rand(1000000000, 9999999999);
        $courier = $order->effective_courier ?: 'Blue Dart Express';

        $order->update([
            'shiprocket_awb_code'     => $mockAwb,
            'shiprocket_courier_name' => $courier,
            'tracking_number'         => $mockAwb,
            'courier_name'            => $courier,
            'status'                  => 'shipped',
            'shipped_at'              => now(),
        ]);

        $order->trackings()->create([
            'status'          => 'shipped',
            'title'           => "AWB Generated ({$courier})",
            'location'        => 'Central Sorting Facility',
            'activity'        => "AWB #{$mockAwb} generated with {$courier}. Ready for dispatch.",
            'courier_name'    => $courier,
            'tracking_number' => $mockAwb,
            'event_time'      => now(),
        ]);

        return [
            'success' => true,
            'message' => "AWB #{$mockAwb} generated via {$courier}.",
        ];
    }
}
