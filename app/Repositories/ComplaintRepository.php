<?php

namespace App\Repositories;

use App\Models\Complaint;

class ComplaintRepository
{
    public function findAll()
    {
        return Complaint::with(['complaintSubject', 'histories'])->orderBy('id', 'DESC')->get();
    }

    public function findById($id)
    {
        return Complaint::with(['complaintSubject', 'histories.respondedBy'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return Complaint::create($data);
    }

    public function update(array $data, $id)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->update($data);
        return $complaint;
    }

    public function delete($id)
    {
        $complaint = Complaint::findOrFail($id);
        return $complaint->delete();
    }

    public function updateStatus($id, $status)
    {
        $complaint = Complaint::findOrFail($id);
        $complaint->status = $status;
        $complaint->save();
        return $complaint;
    }
}
