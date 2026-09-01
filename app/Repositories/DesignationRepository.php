<?php

namespace App\Repositories;

use App\Models\Designation;

class DesignationRepository
{
    public function findForPublic($limit = 10)
    {
        return Designation::where('is_published', 1)->limit($limit)->orderBy('id', 'desc')->get();
    }

    public function findPublished()
    {
        return Designation::where(['is_published' => 1, 'is_approved' => 1])->orderBy('id', 'desc')->get();
    }

    public function findAll()
    {
        return Designation::orderBy('id', 'desc')
            ->get();
    }

    public function findById($id)
    {
        return Designation::find($id);
    }

    public function create($data)
    {
        return Designation::create($data);
    }

    public function update($data, $id)
    {
        $result = Designation::find($id);
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
        $result = Designation::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
