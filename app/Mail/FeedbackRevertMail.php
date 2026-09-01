<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FeedbackRevertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $feedback;
    public $revertMessage;

    public function __construct($feedback, $revertMessage)
    {
        $this->feedback = $feedback;
        $this->revertMessage = $revertMessage;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Response to Your Feedback - CPCB',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.feedback_revert',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
