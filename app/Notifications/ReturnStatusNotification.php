<?php

namespace App\Notifications;

use App\Models\OrderReturn;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReturnStatusNotification extends Notification
{
    use Queueable;

    public OrderReturn $returnRequest;
    public string $status;
    public string $title;
    public string $message;
    public string $link;
    public string $icon;

    /**
     * Create a new notification instance.
     */
    public function __construct(OrderReturn $returnRequest, ?string $customMessage = null)
    {
        $this->returnRequest = $returnRequest->loadMissing(['order', 'user']);
        $this->status = $returnRequest->status;
        $this->link = route('account.returns.show', $returnRequest->id);

        $typeLabel = $returnRequest->isExchange() ? 'Exchange' : 'Return';

        switch ($this->status) {
            case 'requested':
                $this->title = "📋 {$typeLabel} Request #{$returnRequest->return_number} Received";
                $this->message = $customMessage ?: "We have received your {$typeLabel} request for Order #{$returnRequest->order->order_number}. Our team will review it shortly.";
                $this->icon = "📋";
                break;

            case 'approved':
                $this->title = "✅ {$typeLabel} Request #{$returnRequest->return_number} Approved";
                $this->message = $customMessage ?: "Your {$typeLabel} request has been approved. Reverse pickup will be scheduled soon.";
                $this->icon = "✅";
                break;

            case 'pickup_scheduled':
                $courier = $returnRequest->pickup_courier ?: 'Courier Partner';
                $awb = $returnRequest->pickup_awb ? " (AWB: {$returnRequest->pickup_awb})" : '';
                $this->title = "🚚 Reverse Pickup Scheduled";
                $this->message = $customMessage ?: "Pickup has been assigned to {$courier}{$awb}. Please keep the package packed with original tags.";
                $this->icon = "🚚";
                break;

            case 'picked_up':
                $this->title = "📦 Return Parcel Picked Up";
                $this->message = $customMessage ?: "Your parcel has been collected by our courier partner and is on its way to our fulfillment center.";
                $this->icon = "📦";
                break;

            case 'received_at_hub':
                $this->title = "🔍 Item Received & QC Verified";
                $this->message = $customMessage ?: "Your returned drop reached our hub and passed quality verification.";
                $this->icon = "🔍";
                break;

            case 'refunded':
                $amountFormatted = '₹' . number_format($returnRequest->refund_amount, 2);
                $this->title = "💵 Refund of {$amountFormatted} Processed!";
                $this->message = $customMessage ?: "Your refund has been released successfully to your " . ($returnRequest->refund_mode === 'upi' ? "UPI ID ({$returnRequest->upi_id})" : "original payment source") . ".";
                $this->icon = "💵";
                break;

            case 'exchanged':
                $this->title = "🔄 Replacement Exchange Dispatched!";
                $this->message = $customMessage ?: "Your requested replacement size has been dispatched and is on its way!";
                $this->icon = "🔄";
                break;

            case 'rejected':
                $this->title = "❌ {$typeLabel} Request Rejected";
                $this->message = $customMessage ?: ($returnRequest->rejection_reason ? "Reason: {$returnRequest->rejection_reason}" : "Your {$typeLabel} request could not be approved at this time.");
                $this->icon = "❌";
                break;

            default:
                $this->title = "📦 {$typeLabel} Request Updated";
                $this->message = $customMessage ?: "Status: " . ucfirst(str_replace('_', ' ', $returnRequest->status));
                $this->icon = "📦";
                break;
        }
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'          => 'return_status',
            'return_id'     => $this->returnRequest->id,
            'return_number' => $this->returnRequest->return_number,
            'order_id'      => $this->returnRequest->order_id,
            'order_number'  => $this->returnRequest->order?->order_number,
            'status'        => $this->status,
            'title'         => $this->title,
            'message'       => $this->message,
            'link'          => $this->link,
            'icon'          => $this->icon,
            'amount'        => $this->returnRequest->refund_amount,
        ];
    }
}
