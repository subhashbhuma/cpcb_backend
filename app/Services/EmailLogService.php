<?php

namespace App\Services;

use App\DTO\EmailLogDto;
use App\Repositories\EmailLogRepository;

class EmailLogService
{
    protected $emailLogRepository;

    public function __construct()
    {
        $this->emailLogRepository = new EmailLogRepository();
    }

    public function create(EmailLogDto $dto)
    {
        $data = [
            'recipient_email' => $dto->recipient_email,
            'recipient_name' => $dto->recipient_name,
            'email_type' => $dto->email_type,
            'subject' => $dto->subject,
            'status' => $dto->status,
            'error_message' => $dto->error_message,
            'metadata' => $dto->metadata,
            'sent_at' => $dto->sent_at,
        ];

        return $this->emailLogRepository->create($data);
    }
}
