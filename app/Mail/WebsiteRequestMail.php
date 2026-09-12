<?php

namespace App\Mail;

use App\Models\WebsiteRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WebsiteRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public WebsiteRequest $request)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Permintaan Website Baru dari ' . $this->request->nama,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.website-request',
            with: ['request' => $this->request],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
