<?php

namespace App\DTO;

class FeedbackHistoryDto
{
    public $feedback_id;
    public $revert_message;
    public $responded_by;
    public $email_sent;
    public $email_sent_at;

    public function __construct(
        $feedback_id,
        $revert_message,
        $responded_by,
        $email_sent = false,
        $email_sent_at = null
    ) {
        $this->feedback_id = $feedback_id;
        $this->revert_message = $revert_message;
        $this->responded_by = $responded_by;
        $this->email_sent = $email_sent;
        $this->email_sent_at = $email_sent_at;
    }
}
