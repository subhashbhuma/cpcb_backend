<?php

namespace App\DTO;

class ComplaintHistoryDto
{
    public $complaint_id;
    public $revert_message;
    public $responded_by;
    public $email_sent;
    public $email_sent_at;

    public function __construct(
        $complaint_id,
        $revert_message,
        $responded_by,
        $email_sent = false,
        $email_sent_at = null
    ) {
        $this->complaint_id = $complaint_id;
        $this->revert_message = $revert_message;
        $this->responded_by = $responded_by;
        $this->email_sent = $email_sent;
        $this->email_sent_at = $email_sent_at;
    }
}
