<?php

namespace App\Services;

use App\DTO\FeedbackHistoryDto;
use App\Repositories\FeedbackHistoryRepository;
use App\Mail\FeedbackRevertMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class FeedbackHistoryService
{
    private $feedbackHistoryRepository;

    public function __construct()
    {
        $this->feedbackHistoryRepository = new FeedbackHistoryRepository();
    }

    public function findByFeedbackId($feedbackId)
    {
        return $this->feedbackHistoryRepository->findByFeedbackId($feedbackId);
    }

    public function create(FeedbackHistoryDto $feedbackHistoryDto, $feedback)
    {
        $data = [
            'feedback_id' => $feedbackHistoryDto->feedback_id,
            'revert_message' => $feedbackHistoryDto->revert_message,
            'responded_by' => $feedbackHistoryDto->responded_by,
            'email_sent' => false,
            'email_sent_at' => null,
        ];

        $history = $this->feedbackHistoryRepository->create($data);

        // Send email
        try {
            Mail::to($feedback->email)->send(new FeedbackRevertMail($feedback, $feedbackHistoryDto->revert_message));
            $this->feedbackHistoryRepository->markEmailSent($history->id);
        } catch (\Exception $e) {
            Log::error('Failed to send feedback revert email: ' . $e->getMessage());
        }

        return $history;
    }
}
