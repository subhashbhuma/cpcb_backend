<?php

namespace App\Services;

use App\DTO\ComplaintHistoryDto;
use App\Repositories\ComplaintHistoryRepository;
use App\Mail\ComplaintRevertMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ComplaintHistoryService
{
    private $complaintHistoryRepository;

    public function __construct()
    {
        $this->complaintHistoryRepository = new ComplaintHistoryRepository();
    }

    public function findByComplaintId($complaintId)
    {
        return $this->complaintHistoryRepository->findByComplaintId($complaintId);
    }

    public function create(ComplaintHistoryDto $complaintHistoryDto, $complaint)
    {
        $data = [
            'complaint_id' => $complaintHistoryDto->complaint_id,
            'revert_message' => $complaintHistoryDto->revert_message,
            'responded_by' => $complaintHistoryDto->responded_by,
            'email_sent' => false,
            'email_sent_at' => null,
        ];

        $history = $this->complaintHistoryRepository->create($data);

        // Send email
        try {
            Mail::to($complaint->email)->send(new ComplaintRevertMail($complaint, $complaintHistoryDto->revert_message));
            $this->complaintHistoryRepository->markEmailSent($history->id);
        } catch (\Exception $e) {
            Log::error('Failed to send complaint revert email: ' . $e->getMessage());
        }

        return $history;
    }
}
