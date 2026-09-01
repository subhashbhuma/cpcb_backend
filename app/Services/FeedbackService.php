<?php

namespace App\Services;

use App\DTO\FeedbackDto;
use App\Repositories\FeedbackRepository;

class FeedbackService
{
    private $feedbackRepository;

    public function __construct()
    {
        $this->feedbackRepository = new FeedbackRepository();
    }

    public function findAll()
    {
        return $this->feedbackRepository->findAll();
    }

    public function findById($id)
    {
        return $this->feedbackRepository->findById($id);
    }

    public function create(FeedbackDto $feedbackDto)
    {
        $data = [
            'full_name' => $feedbackDto->full_name,
            'email' => $feedbackDto->email,
            'phone' => $feedbackDto->phone,
            'message' => $feedbackDto->message,
            'file_name' => $feedbackDto->file_name,
            'status' => $feedbackDto->status,
        ];
        return $this->feedbackRepository->create($data);
    }

    public function update(FeedbackDto $feedbackDto, $id)
    {
        $data = [
            'full_name' => $feedbackDto->full_name,
            'email' => $feedbackDto->email,
            'phone' => $feedbackDto->phone,
            'message' => $feedbackDto->message,
            'file_name' => $feedbackDto->file_name,
            'status' => $feedbackDto->status,
        ];

        return $this->feedbackRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->feedbackRepository->delete($id);
    }

    public function updateStatus($id, $status)
    {
        return $this->feedbackRepository->updateStatus($id, $status);
    }
}
