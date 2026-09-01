<?php

namespace App\DTO;

class SmsLogDto
{
    public $recipient_sms;
    public $recipient_name;
    public $sms_type;
    public $subject;
    public $status;
    public $error_message;
    public $metadata;
    public $sent_at;

    public function __construct(
        $recipient_sms,
        $sms_type,
        $subject,
        $status = 'failed',
        $recipient_name = null,
        $error_message = null,
        $metadata = null,
        $sent_at = null
    ) {
        $this->recipient_sms = $recipient_sms;
        $this->recipient_name = $recipient_name;
        $this->sms_type = $sms_type;
        $this->subject = $subject;
        $this->status = $status;
        $this->error_message = $error_message;
        $this->metadata = $metadata;
        $this->sent_at = $sent_at;
    }
}
