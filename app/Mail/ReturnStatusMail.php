<?php

namespace App\Mail;

use App\Models\OrderReturn;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReturnStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public OrderReturn $returnRequest;

    /**
     * Create a new message instance.
     */
    public function __construct(OrderReturn $returnRequest)
    {
        $this->returnRequest = $returnRequest->loadMissing([
            'order.address',
            'user',
            'items.product',
            'items.originalVariant',
            'items.exchangeVariant'
        ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $typeLabel = $this->returnRequest->isExchange() ? 'Exchange' : 'Return';
        $status = $this->returnRequest->status;

        $subject = match ($status) {
            'requested'        => "📋 {$typeLabel} Request #{$this->returnRequest->return_number} Received — ThreadAX",
            'approved'         => "✅ {$typeLabel} Request #{$this->returnRequest->return_number} Approved — ThreadAX",
            'pickup_scheduled' => "🚚 Reverse Pickup Scheduled for #{$this->returnRequest->return_number} — ThreadAX",
            'refunded'         => "💵 Refund Processed for {$typeLabel} #{$this->returnRequest->return_number} — ThreadAX",
            'exchanged'        => "🔄 Replacement Exchange Dispatched for #{$this->returnRequest->return_number} — ThreadAX",
            'rejected'         => "Update on your {$typeLabel} Request #{$this->returnRequest->return_number} — ThreadAX",
            default            => "Update on your {$typeLabel} #{$this->returnRequest->return_number} — ThreadAX",
        };

        return new Envelope(subject: $subject);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.returns.status',
            with: [
                'returnRequest' => $this->returnRequest,
                'order'         => $this->returnRequest->order,
            ],
        );
    }
}
