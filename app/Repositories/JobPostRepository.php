<?php

namespace App\Repositories;

use App\Models\JobPost;

class JobPostRepository
{
    public function findAll()
    {
        return JobPost::orderBy('id', 'DESC')->get();
    }

    public function findById($id)
    {
        return JobPost::find($id);
    }

    public function create($data)
    {
        return JobPost::create($data);
    }

    public function update($data, $id)
    {
        $result = JobPost::find($id);
        if ($result) {
            return $result->update($data);
        }
        return false;
    }

    public function delete($id)
    {
        $result = JobPost::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }

    public function findByJobId($jobId)
    {
        return JobPost::where('job_id', $jobId)->get();
    }
}
