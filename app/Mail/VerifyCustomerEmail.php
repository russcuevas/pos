<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyCustomerEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $url;

    public function __construct($customer, $url)
    {
        $this->customer = $customer;
        $this->url = $url;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify Your Email Address - Sammer Store',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.verify_customer',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
