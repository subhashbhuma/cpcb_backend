<?php

namespace App\Repositories;

use App\Models\Feedback;

class FeedbackRepository
{
    public function findAll()
    {
        return Feedback::with(['histories'])->orderBy('id', 'DESC')->get();
    }

    public function findById($id)
    {
        return Feedback::with(['histories.respondedBy'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Feedback::create($data);
    }

    public function update(array $data, $id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->update($data);
        return $feedback;
    }

    public function delete($id)
    {
        $feedback = Feedback::findOrFail($id);
        return $feedback->delete();
    }

    public function updateStatus($id, $status)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->status = $status;
        $feedback->save();
        return $feedback;
    }
}
