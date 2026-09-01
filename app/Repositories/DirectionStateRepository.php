<?php

namespace App\Repositories;

use App\Models\DirectionState;

class DirectionStateRepository
{
    public function findForPublic($limit = 10)
    {
        return DirectionState::where('is_published', 1)->limit($limit)->orderBy('created_at', 'desc')->get();
    }

    public function findAll()
    {
        return DirectionState::orderBy('id', 'DESC')->get();
    }

    public function findById($id)
    {
        return DirectionState::find($id);
    }

    public function fetchByIdForPublic($id)
    {
        return DirectionState::where('is_published', 1)->find($id);
    }

    public function create($data)
    {
        return DirectionState::create($data);
    }

    public function update($data, $id)
    {
        $directionState = DirectionState::find($id);
        if ($directionState) {
            $directionState->update($data);
            return $directionState;
        }
        return null;
    }

    public function delete($id)
    {
        $directionState = DirectionState::find($id);
        if ($directionState) {
            return $directionState->delete();
        }
        return false;
    }
}
