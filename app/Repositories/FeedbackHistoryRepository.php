<?php

namespace App\Repositories;

use App\Models\FeedbackHistory;

class FeedbackHistoryRepository
{
    public function findByFeedbackId($feedbackId)
    {
        return FeedbackHistory::where('feedback_id', $feedbackId)
            ->with('respondedBy')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function create(array $data)
    {
        return FeedbackHistory::create($data);
    }

    public function markEmailSent($id)
    {
        $history = FeedbackHistory::findOrFail($id);
        $history->email_sent = true;
        $history->email_sent_at = now();
        $history->save();
        return $history;
    }
}
