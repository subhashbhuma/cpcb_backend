<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Mail\Mailables\Address;

class NewFeedbackNotificationMail extends Mailable
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
            view: 'emails.feedback_notification',
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        if ($this->feedback->file_name) {
            $path = Config::get('file_paths')['FEEDBACK_FILE_PATH'] . '/' . $this->feedback->file_name;
            $fullPath = storage_path('app/public/' . $path);
            if (file_exists($fullPath)) {
                $attachments[] = Attachment::fromPath($fullPath)
                    ->as($this->feedback->file_name);
            }
        }

        return $attachments;
    }
}