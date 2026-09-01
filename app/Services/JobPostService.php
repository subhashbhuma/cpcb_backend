<?php

namespace App\Services;

use App\DTO\JobPostDto;
use App\Repositories\JobPostRepository;

class JobPostService
{
    private $jobPostRepository;

    public function __construct()
    {
        $this->jobPostRepository = new JobPostRepository();
    }

    public function findAll()
    {
        return $this->jobPostRepository->findAll();
    }

    public function findById($id)
    {
        return $this->jobPostRepository->findById($id);
    }

    public function create(JobPostDto $jobPostDto)
    {
        return $this->jobPostRepository->create([
            'job_id' => $jobPostDto->job_id,
            'title' => $jobPostDto->title,
            'title_hi' => $jobPostDto->title_hi,
            'remarks' => $jobPostDto->remarks,
            'is_approved' => $jobPostDto->is_approved,
            'is_published' => $jobPostDto->is_published,
            'created_by' => $jobPostDto->created_by,
            'created_at' => $jobPostDto->created_at,
            'updated_by' => $jobPostDto->updated_by,
            'updated_at' => $jobPostDto->updated_at,
        ]);
    }

    public function update(JobPostDto $jobPostDto, $id)
    {
        return $this->jobPostRepository->update([
            'job_id' => $jobPostDto->job_id,
            'title' => $jobPostDto->title,
            'title_hi' => $jobPostDto->title_hi,
            'remarks' => null,
            'is_approved' => 0,
            'is_published' => 0,
            'publish_remark' => null,
            'updated_by' => $jobPostDto->updated_by,
            'updated_at' => $jobPostDto->updated_at,
        ], $id);
    }

    public function delete($id)
    {
        return $this->jobPostRepository->delete($id);
    }

    public function findByJobId($jobId)
    {
        return $this->jobPostRepository->findByJobId($jobId);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $data = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->jobPostRepository->update($data, $id);
    }

    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null)
    {
        $data = [
            'is_published' => $isPublished,
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->jobPostRepository->update($data, $id);
    }
}
