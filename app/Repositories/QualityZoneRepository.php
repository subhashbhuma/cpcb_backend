<?php

namespace App\Repositories;

use App\Models\QualityZone;

class QualityZoneRepository
{
    public function findForPublicWithPagination($perPage = 10)
    {
        return QualityZone::where('is_published', 1)->orderBy('id', 'desc')->paginate($perPage);
    }

    public function findForPublic($limit = null)
    {
        if ($limit) {
            return QualityZone::where('is_published', 1)->limit($limit)->orderBy('id', 'desc')->get();
        }
        return QualityZone::where('is_published', 1)->orderBy('id', 'desc')->get();
    }

    public function findAll()
    {
        return QualityZone::orderBy('id', 'DESC')->get();
    }

    public function findById($id)
    {
        return QualityZone::find($id);
    }

    public function create($data)
    {
        return QualityZone::create($data);
    }

    public function update($data, $id)
    {
        $result = QualityZone::find($id);
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
        $result = QualityZone::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
