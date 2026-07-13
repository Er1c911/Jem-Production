<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class PurchaseItemsDelivered extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public array $items,
        public string $customerEmail,
        public float $totalAmount
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('jem.production26@gmail.com', 'JEM Production'),
            subject: 'Pembelian Anda Sudah Disetujui - JEM Production',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.purchase-items-delivered',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
