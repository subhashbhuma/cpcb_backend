<?php

namespace App\Repositories;

use App\Models\ComplaintFormSubject;

class ComplaintFormSubjectRepository
{
    public function findForPublic($limit = 10)
    {
        return ComplaintFormSubject::where('is_published', 1)->limit($limit)->orderBy('title', 'asc')->get();
    }

    public function findAll()
    {
        return ComplaintFormSubject::orderBy('id', 'DESC')->get();
    }

    public function findById($id)
    {
        return ComplaintFormSubject::find($id);
    }

    public function fetchByIdForPublic($id)
    {
        return ComplaintFormSubject::where('is_published', 1)->find($id);
    }

    public function create($data)
    {
        return ComplaintFormSubject::create($data);
    }

    public function update($data, $id)
    {
        $complaintFormSubject = ComplaintFormSubject::find($id);
        if ($complaintFormSubject) {
            $complaintFormSubject->update($data);
            return $complaintFormSubject;
        }
        return null;
    }

    public function delete($id)
    {
        $complaintFormSubject = ComplaintFormSubject::find($id);
        if ($complaintFormSubject) {
            return $complaintFormSubject->delete();
        }
        return false;
    }
}
