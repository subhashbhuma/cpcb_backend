<?php

namespace App\DTO;

class FeedbackDto
{
    public $full_name;
    public $email;
    public $phone;
    public $message;
    public $file_name;
    public $status;

    public function __construct(
        $full_name,
        $email,
        $phone = null,
        $message,
        $file_name = null,
        $status = 'pending'
    ) {
        $this->full_name = $full_name;
        $this->email = $email;
        $this->phone = $phone;
        $this->message = $message;
        $this->file_name = $file_name;
        $this->status = $status;
    }
}
