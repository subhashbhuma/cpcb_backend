<?php

namespace App\Repositories;

use App\Models\AnnualReport;


class AnnualReportRepository
{
    public function findForPublic($limit = 10)
    {
        return AnnualReport::where('is_published', 1)->limit($limit)->orderBy('created_at', 'desc')->get();
    }

    // public function findForPublicHomepage($limit = 10)
    // {
    //     return AnnualReport::where([
    //         'is_published' => 1
    //     ])->limit($limit)->orderBy('id', 'desc')->get();
    // }

    public function findAll()
    {
        return AnnualReport::orderBy('id', 'DESC')->get();
    }

    public function findById($id)
    {
        return AnnualReport::find($id);
    }

    public function create($data)
    {
        return AnnualReport::create($data);
    }

    public function update($data, $id)
    {
        $result = AnnualReport::find($id);
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
        $result = AnnualReport::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
