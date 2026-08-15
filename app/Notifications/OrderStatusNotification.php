<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification
{
    use Queueable;

    public Order $order;
    public string $status;
    public string $title;
    public string $message;
    public ?string $link;
    public string $icon;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, ?string $customMessage = null)
    {
        $this->order = $order->loadMissing(['items.variant.product', 'address', 'payment']);
        $this->status = $order->status;

        $trackingUrl = route('frontend.tracking', ['order_number' => $order->order_number]);

        switch ($this->status) {
            case 'processing':
                $this->title = "✨ Order #{$order->order_number} Confirmed";
                $this->message = $customMessage ?: "Your ThreadAX drop has been confirmed and packed for shipment.";
                $this->link = $trackingUrl;
                $this->icon = "🛍️";
                break;

            case 'shipped':
                $courier = $order->effective_courier ?: 'Express Courier';
                $awb = $order->effective_awb ?: 'TX-EXP';
                $this->title = "🚚 Order #{$order->order_number} Dispatched & Shipped";
                $this->message = $customMessage ?: "Handed over to {$courier} (AWB: {$awb}). Track your live delivery.";
                $this->link = $order->tracking_url ?: $trackingUrl;
                $this->icon = "🚚";
                break;

            case 'delivered':
                $this->title = "🎉 Order #{$order->order_number} Delivered!";
                $this->message = $customMessage ?: "Your streetwear package has arrived at your doorstep. Enjoy your fit!";
                $this->link = route('account.orders.show', $order->id);
                $this->icon = "🎉";
                break;

            case 'cancelled':
                $this->title = "⚠️ Order #{$order->order_number} Cancelled";
                $this->message = $customMessage ?: "Your order was cancelled." . ($order->payment_status === 'paid' ? " Refund has been initiated." : "");
                $this->link = route('account.orders');
                $this->icon = "⚠️";
                break;

            case 'pending':
            default:
                $this->title = "🛍️ Order #{$order->order_number} Placed";
                $this->message = $customMessage ?: "We have received your order and our warehouse team is preparing your drop.";
                $this->link = $trackingUrl;
                $this->icon = "🛍️";
                break;
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type'         => 'order_status',
            'order_id'     => $this->order->id,
            'order_number' => $this->order->order_number,
            'status'       => $this->status,
            'title'        => $this->title,
            'message'      => $this->message,
            'link'         => $this->link,
            'icon'         => $this->icon,
            'amount'       => $this->order->total,
        ];
    }
}
