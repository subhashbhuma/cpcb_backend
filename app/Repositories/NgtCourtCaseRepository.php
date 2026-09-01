<?php

namespace App\Repositories;

use App\Models\NgtCourtCase;

class NgtCourtCaseRepository
{
    public function findForPublicWithPagination($perPage = 10)
    {
        return NgtCourtCase::where('is_published', 1)->orderBy('publish_date', 'desc')->paginate($perPage);
    }

    public function findForPublic($limit = null)
    {
        if ($limit) {
            return NgtCourtCase::where('is_published', 1)->limit($limit)->orderBy('publish_date', 'desc')->get();
        }
        return NgtCourtCase::where('is_published', 1)->orderBy('publish_date', 'desc')->get();
    }

    public function findAll()
    {
        return NgtCourtCase::orderBy('id', 'DESC')->get();
    }

    public function findById($id)
    {
        return NgtCourtCase::find($id);
    }

    public function create($data)
    {
        return NgtCourtCase::create($data);
    }

    public function update($data, $id)
    {
        $result = NgtCourtCase::find($id);
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
        $result = NgtCourtCase::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
