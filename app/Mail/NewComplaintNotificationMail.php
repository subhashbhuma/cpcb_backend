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

class NewComplaintNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $complaint;
    public $subjectData;

    public function __construct($complaint, $subjectData)
    {
        $this->complaint = $complaint;
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
            view: 'emails.complaint_notification',
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        if ($this->complaint->file_name) {
            $path = Config::get('file_paths')['COMPLAINT_FILE_PATH'] . '/' . $this->complaint->file_name;
            $fullPath = storage_path('app/public/' . $path);
            if (file_exists($fullPath)) {
                $attachments[] = Attachment::fromPath($fullPath)
                    ->as($this->complaint->file_name);
            }
        }

        return $attachments;
    }
}
