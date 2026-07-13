<?php

namespace App\Mail;

use App\Models\PaymentConfirmation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentCancelledNotice extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PaymentConfirmation $payment,
        public string $cancelReason
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('jem.production26@gmail.com', 'JEM Production'),
            subject: 'Konfirmasi Pembayaran Ditolak - JEM Production',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-cancelled-notice',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
