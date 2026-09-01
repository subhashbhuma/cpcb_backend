<?php

namespace App\DTO;

class EmailLogDto
{
    public $recipient_email;
    public $recipient_name;
    public $email_type;
    public $subject;
    public $status;
    public $error_message;
    public $metadata;
    public $sent_at;

    public function __construct(
        $recipient_email,
        $email_type,
        $subject,
        $status = 'failed',
        $recipient_name = null,
        $error_message = null,
        $metadata = null,
        $sent_at = null
    ) {
        $this->recipient_email = $recipient_email;
        $this->recipient_name = $recipient_name;
        $this->email_type = $email_type;
        $this->subject = $subject;
        $this->status = $status;
        $this->error_message = $error_message;
        $this->metadata = $metadata;
        $this->sent_at = $sent_at;
    }
}
