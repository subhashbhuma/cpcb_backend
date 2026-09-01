<?php

namespace App\Repositories;

use App\Models\Tender;

class TenderRepository
{
    public function findForPublicWithPagination($perPage = 10)
    {
        return Tender::with('division')->where('is_published', 1)->orderBy('publish_date', 'desc')->paginate($perPage);
    }

    public function findForPublic($limit = null)
    {
        if ($limit) {
            return Tender::with('division')->where('is_published', 1)->limit($limit)->orderBy('publish_date', 'desc')->get();
        }
        return Tender::with('division')->where('is_published', 1)->orderBy('publish_date', 'desc')->get();
    }

    public function findAll()
    {
        return Tender::with('division')->orderBy('id', 'DESC')->get();
    }

    public function findById($id)
    {
        return Tender::with('division')->find($id);
    }

    public function create($data)
    {
        return Tender::create($data);
    }

    public function update($data, $id)
    {
        $result = Tender::find($id);
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
        $result = Tender::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
