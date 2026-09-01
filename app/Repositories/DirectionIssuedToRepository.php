<?php

namespace App\Repositories;

use App\Models\DirectionIssuedTo;

class DirectionIssuedToRepository
{
    public function findForPublic($limit = 10)
    {
        return DirectionIssuedTo::where('is_published', 1)->limit($limit)->orderBy('created_at', 'desc')->get();
    }

    public function findAll()
    {
        return DirectionIssuedTo::with('directionActType')->orderBy('id', 'DESC')->get();
    }

    public function findByActType($actTypeId)
    {
        return DirectionIssuedTo::where('direction_act_type_id', $actTypeId)
            ->where('is_published', 1)
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function findById($id)
    {
        return DirectionIssuedTo::find($id);
    }

    public function fetchByIdForPublic($id)
    {
        return DirectionIssuedTo::where('is_published', 1)->find($id);
    }

    public function create($data)
    {
        return DirectionIssuedTo::create($data);
    }

    public function update($data, $id)
    {
        $directionIssuedTo = DirectionIssuedTo::find($id);
        if ($directionIssuedTo) {
            $directionIssuedTo->update($data);
            return $directionIssuedTo;
        }
        return null;
    }

    public function delete($id)
    {
        $directionIssuedTo = DirectionIssuedTo::find($id);
        if ($directionIssuedTo) {
            return $directionIssuedTo->delete();
        }
        return false;
    }
}
