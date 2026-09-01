<?php

namespace App\DTO;

class ComplaintDto
{
    public $complaint_subject_id;
    public $full_name;
    public $email;
    public $phone;
    public $location;
    public $message;
    public $file_name;
    public $status;

    public function __construct(
        $complaint_subject_id,
        $full_name,
        $email,
        $phone = null,
        $location = null,
        $message,
        $file_name = null,
        $status = 'pending'
    ) {
        $this->complaint_subject_id = $complaint_subject_id;
        $this->full_name = $full_name;
        $this->email = $email;
        $this->phone = $phone;
        $this->location = $location;
        $this->message = $message;
        $this->file_name = $file_name;
        $this->status = $status;
    }
}
