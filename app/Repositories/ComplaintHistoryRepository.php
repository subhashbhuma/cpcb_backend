<?php

namespace App\Repositories;

use App\Models\ComplaintHistory;

class ComplaintHistoryRepository
{
    public function findByComplaintId($complaintId)
    {
        return ComplaintHistory::where('complaint_id', $complaintId)
            ->with('respondedBy')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function create(array $data)
    {
        return ComplaintHistory::create($data);
    }

    public function markEmailSent($id)
    {
        $history = ComplaintHistory::findOrFail($id);
        $history->email_sent = true;
        $history->email_sent_at = now();
        $history->save();
        return $history;
    }
}
