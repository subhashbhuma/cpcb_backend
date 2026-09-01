<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Mail\Mailables\Address;

class FeedbackConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $feedback;
    public function __construct($feedback)
    {
        $this->feedback = $feedback;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(Config::get('mail.query_form.to'), Config::get('mail.query_form.subject')),
            subject: Config::get('mail.query_form.subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.feedback_confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
