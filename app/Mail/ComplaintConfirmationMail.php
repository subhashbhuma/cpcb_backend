<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Mail\Mailables\Address;

class ComplaintConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $result;
    public $subjectData;

    public function __construct($result, $subjectData)
    {
        $this->result = $result;
        $this->subjectData = $subjectData;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(Config::get('mail.complaint_form.from'), Config::get('mail.complaint_form.subject')),
            subject: $this->subjectData->title ?? 'Complaint Received - CPCB',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.complaint_confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
