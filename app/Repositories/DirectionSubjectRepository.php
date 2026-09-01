<?php

namespace App\Repositories;

use App\Models\DirectionSubject;

class DirectionSubjectRepository
{
    public function findForPublic($limit = 10)
    {
        return DirectionSubject::where('is_published', 1)->limit($limit)->orderBy('created_at', 'desc')->get();
    }

    public function findAll()
    {
        return DirectionSubject::with('directionActType')->orderBy('id', 'DESC')->get();
    }

    public function findByActType($actTypeId)
    {
        return DirectionSubject::where('direction_act_type_id', $actTypeId)
            ->where('is_published', 1)
            ->orderBy('id', 'DESC')
            ->get();
    }

    public function findById($id)
    {
        return DirectionSubject::find($id);
    }

    public function fetchByIdForPublic($id)
    {
        return DirectionSubject::where('is_published', 1)->find($id);
    }

    public function create($data)
    {
        return DirectionSubject::create($data);
    }

    public function update($data, $id)
    {
        $directionSubject = DirectionSubject::find($id);
        if ($directionSubject) {
            $directionSubject->update($data);
            return $directionSubject;
        }
        return null;
    }

    public function delete($id)
    {
        $directionSubject = DirectionSubject::find($id);
        if ($directionSubject) {
            return $directionSubject->delete();
        }
        return false;
    }
}
