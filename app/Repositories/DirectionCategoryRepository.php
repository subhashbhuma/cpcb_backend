<?php

namespace App\Repositories;

use App\Models\DirectionCategory;

class DirectionCategoryRepository
{
    public function findForPublic($limit = 10)
    {
        return DirectionCategory::where('is_published', 1)->limit($limit)->orderBy('created_at', 'desc')->get();
    }

    public function findAll()
    {
        return DirectionCategory::with('directionActType')->orderBy('id', 'DESC')->get();
    }

    public function findByActType($actTypeId)
    {
        return DirectionCategory::where('direction_act_type_id', $actTypeId)
            ->where('is_published', 1)
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function findById($id)
    {
        return DirectionCategory::find($id);
    }

    public function fetchByIdForPublic($id)
    {
        return DirectionCategory::where('is_published', 1)->find($id);
    }

    public function create($data)
    {
        return DirectionCategory::create($data);
    }

    public function update($data, $id)
    {
        $directionCategory = DirectionCategory::find($id);
        if ($directionCategory) {
            $directionCategory->update($data);
            return $directionCategory;
        }
        return null;
    }

    public function delete($id)
    {
        $directionCategory = DirectionCategory::find($id);
        if ($directionCategory) {
            return $directionCategory->delete();
        }
        return false;
    }
}
