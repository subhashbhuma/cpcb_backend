<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ComplaintRevertMail extends Mailable
{
    use Queueable, SerializesModels;

    public $Complaint;
    public $revertMessage;

    public function __construct($Complaint, $revertMessage)
    {
        $this->Complaint = $Complaint;
        $this->revertMessage = $revertMessage;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Response to Your Complaint - CPCB',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.Complaint_revert',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
