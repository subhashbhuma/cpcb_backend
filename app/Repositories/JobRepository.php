<?php

namespace App\Repositories;

use App\Models\Job;

class JobRepository
{
    public function findForPublic($limit = 10)
    {
        return Job::where('is_published', 1)->limit($limit)->orderBy('created_at', 'desc')->get();
    }

    // public function findForPublicHomepage($limit = 10)
    // {
    //     return Job::where([
    //         'is_published' => 1
    //     ])->limit($limit)->orderBy('id', 'desc')->get();
    // }

    public function findAll()
    {
        return Job::orderBy('id', 'DESC')->get();
    }

    public function findById($id)
    {
        return Job::find($id);
    }

    public function create($data)
    {
        return Job::create($data);
    }

    public function update($data, $id)
    {
        $result = Job::find($id);
        if ($result) {
            $result = $result->update($data);
            if (!$result) {
                return false;
            }
            return $result;
        }
        return false;
    }

    public function delete($id)
    {
        $result = Job::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
