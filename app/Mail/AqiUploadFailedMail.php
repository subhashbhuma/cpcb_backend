<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AqiUploadFailedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $errorMessage;
    public $fileName;

    public function __construct($errorMessage, $fileName = 'Unknown File')
    {
        $this->errorMessage = $errorMessage;
        $this->fileName = $fileName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'AQI Upload Fail',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.aqi_upload_failed',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
