<?php

namespace App\Repositories;

use App\Models\DirectionActType;

class DirectionActTypeRepository
{
    public function findForPublic($limit = 10)
    {
        return DirectionActType::where('is_published', 1)->limit($limit)->orderBy('created_at', 'desc')->get();
    }

    public function findAll()
    {
        return DirectionActType::orderBy('id', 'DESC')->get();
    }

    public function findById($id)
    {
        return DirectionActType::find($id);
    }

    public function fetchByIdForPublic($id)
    {
        return DirectionActType::where('is_published', 1)->find($id);
    }

    public function create($data)
    {
        return DirectionActType::create($data);
    }

    public function update($data, $id)
    {
        $directionActType = DirectionActType::find($id);
        if ($directionActType) {
            $directionActType->update($data);
            return $directionActType;
        }
        return null;
    }

    public function delete($id)
    {
        $directionActType = DirectionActType::find($id);
        if ($directionActType) {
            return $directionActType->delete();
        }
        return false;
    }
}
