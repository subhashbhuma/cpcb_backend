<?php

namespace App\Repositories;

use App\Models\DirectionType;

class DirectionTypeRepository
{
    public function findAll()
    {
        return DirectionType::with('directionActType')->orderBy('id', 'DESC')->get();
    }

    public function findByActType($actTypeId)
    {
        return DirectionType::where('direction_act_type_id', $actTypeId)
            ->where('is_published', 1)
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function findForPublic($limit = 10)
    {
        return DirectionType::where('is_published', 1)->limit($limit)->orderBy('created_at', 'desc')->get();
    }

    public function findById($id)
    {
        return DirectionType::find($id);
    }

    public function findPublished()
    {
        return DirectionType::where('is_published', 1)->orderBy('created_at', 'desc')->get();
    }

    public function create($data)
    {
        return DirectionType::create($data);
    }

    public function update($data, $id)
    {
        $result = DirectionType::find($id);
        if ($result) {
            $updated = $result->update($data);
            if (!$updated) {
                return false;
            }
            return $result;
        }
        return false;
    }

    public function delete($id)
    {
        $result = DirectionType::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
