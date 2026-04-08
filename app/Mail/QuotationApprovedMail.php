<?php

namespace App\Mail;

use App\Models\Models\KMJ\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuotationApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Quotation $quotation) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Quotation Approved — ' . $this->quotation->customer->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.quotation.approved',
        );
    }
}
