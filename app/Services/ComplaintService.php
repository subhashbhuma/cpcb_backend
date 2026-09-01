<?php

namespace App\Services;

use App\DTO\ComplaintDto;
use App\Repositories\ComplaintRepository;

class ComplaintService
{
    private $complaintRepository;

    public function __construct()
    {
        $this->complaintRepository = new ComplaintRepository();
    }

    public function findAll()
    {
        return $this->complaintRepository->findAll();
    }

    public function findById($id)
    {
        return $this->complaintRepository->findById($id);
    }

    public function create(ComplaintDto $complaintDto)
    {
        $data = [
            'complaint_subject_id' => $complaintDto->complaint_subject_id,
            'full_name' => $complaintDto->full_name,
            'email' => $complaintDto->email,
            'phone' => $complaintDto->phone,
            'location' => $complaintDto->location,
            'message' => $complaintDto->message,
            'file_name' => $complaintDto->file_name,
            'status' => $complaintDto->status,
        ];
        return $this->complaintRepository->create($data);
    }

    public function update(ComplaintDto $complaintDto, $id)
    {
        $data = [
            'complaint_subject_id' => $complaintDto->complaint_subject_id,
            'full_name' => $complaintDto->full_name,
            'email' => $complaintDto->email,
            'phone' => $complaintDto->phone,
            'location' => $complaintDto->location,
            'message' => $complaintDto->message,
            'file_name' => $complaintDto->file_name,
            'status' => $complaintDto->status,
        ];

        return $this->complaintRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->complaintRepository->delete($id);
    }

    public function updateStatus($id, $status)
    {
        return $this->complaintRepository->updateStatus($id, $status);
    }
}
