<?php

namespace App\Repositories;

use App\Models\FortnightlyReport;

class FortnightlyReportRepository
{
    public function findForPublicWithPagination($perPage = 10)
    {
        return FortnightlyReport::where('is_published', 1)->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function findForPublic($limit = null)
    {
        if ($limit) {
            return FortnightlyReport::where('is_published', 1)->limit($limit)->orderBy('created_at', 'desc')->get();
        }
        return FortnightlyReport::where('is_published', 1)->orderBy('created_at', 'desc')->get();
    }

    public function findAll()
    {
        return FortnightlyReport::orderBy('id', 'DESC')->get();
    }

    public function findById($id)
    {
        return FortnightlyReport::find($id);
    }

    public function create($data)
    {
        return FortnightlyReport::create($data);
    }

    public function update($data, $id)
    {
        $result = FortnightlyReport::find($id);
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
        $result = FortnightlyReport::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
