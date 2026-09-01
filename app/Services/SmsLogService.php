<?php

namespace App\Services;

use App\DTO\SmsLogDto;
use App\Repositories\SmsLogRepository;

class SmsLogService
{
    protected $smsLogRepository;

    public function __construct()
    {
        $this->smsLogRepository = new SmsLogRepository();
    }

    public function create(SmsLogDto $dto)
    {
        $data = [
            'recipient_sms' => $dto->recipient_sms,
            'recipient_name' => $dto->recipient_name,
            'sms_type' => $dto->sms_type,
            'subject' => $dto->subject,
            'status' => $dto->status,
            'error_message' => $dto->error_message,
            'metadata' => $dto->metadata,
            'sent_at' => $dto->sent_at,
        ];

        return $this->smsLogRepository->create($data);
    }
}
