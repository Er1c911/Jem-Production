<?php

namespace App\Mail;

use App\Models\PaymentConfirmation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class AdminPaymentSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PaymentConfirmation $payment
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('jem.production26@gmail.com', 'JEM Production'),
            subject: 'Konfirmasi Pembayaran Baru - JEM Production',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-payment-submitted',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
