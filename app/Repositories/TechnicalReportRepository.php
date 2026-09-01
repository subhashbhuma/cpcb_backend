<?php

namespace App\Repositories;

use App\Models\TechnicalReport;

class TechnicalReportRepository
{
    public function findForPublic($limit = 10)
    {
        return TechnicalReport::where('is_published', 1)->limit($limit)->orderBy('created_at', 'desc')->get();
    }

    // public function findForPublicHomepage($limit = 10)
    // {
    //     return Job::where([
    //         'is_published' => 1
    //     ])->limit($limit)->orderBy('id', 'desc')->get();
    // }

    public function findAll()
    {
        return TechnicalReport::with(['subjectArea', 'division'])->orderBy('id', 'DESC')->get();
    }

    public function findById($id)
    {
        return TechnicalReport::find($id);
    }

    public function create($data)
    {
        return TechnicalReport::create($data);
    }

    public function update($data, $id)
    {
        $result = TechnicalReport::find($id);
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
        $result = TechnicalReport::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
