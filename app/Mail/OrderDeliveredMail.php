<?php

namespace App\Mail;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderDeliveredMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order->loadMissing(['items.variant.product', 'user', 'address', 'payment']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "🎉 Order Delivered! Your ThreadAX Drop #{$this->order->order_number} has arrived 📦",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.delivered',
            with: [
                'order' => $this->order,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        try {
            $pdf = Pdf::loadView('admin.pages.orders.invoice', [
                'order' => $this->order,
            ]);

            return [
                Attachment::fromData(fn () => $pdf->output(), "invoice-{$this->order->order_number}.pdf")
                    ->withMime('application/pdf'),
            ];
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to generate PDF attachment for delivered email: " . $e->getMessage());
            return [];
        }
    }
}
