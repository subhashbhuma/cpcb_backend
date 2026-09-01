<?php

namespace App\Repositories;

use App\Models\SubjectArea;

class SubjectAreaRepository
{
    public function findForPublic($limit = 10)
    {
        return SubjectArea::where('is_published', 1)->limit($limit)->orderBy('id', 'desc')->get();
    }

    public function findPublished()
    {
        return SubjectArea::where(['is_published' => 1, 'is_approved' => 1])->orderBy('id', 'desc')->get();
    }

    public function findAll()
    {
        return SubjectArea::orderBy('id', 'desc')
            ->get();
    }
    public function findById($id)
    {
        return SubjectArea::find($id);
    }

    public function create($data)
    {
        return SubjectArea::create($data);
    }

    public function update($data, $id)
    {
        $result = SubjectArea::find($id);
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
        $result = SubjectArea::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
