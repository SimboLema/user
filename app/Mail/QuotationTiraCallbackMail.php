<?php

namespace App\Mail;

use App\Models\Models\KMJ\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuotationTiraCallbackMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Quotation $quotation) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'TIRA Response Received — ' . $this->quotation->cover_note_reference,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.quotation.tira_callback',
        );
    }
}
