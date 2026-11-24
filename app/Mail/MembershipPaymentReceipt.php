<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MembershipPaymentReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Payment $payment
    ) {
        $this->payment->loadMissing(
            'membershipTransaction.member'
        );
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'MemberHub - Membership Payment Receipt'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.membership-payment-receipt'
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
