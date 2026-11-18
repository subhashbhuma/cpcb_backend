<?php

namespace App\Repositories;

use App\Models\EprPortal;

class EprPortalRepository
{
    public function findForPublic($limit=null)
    {
        return EprPortal::where('is_published', 1)->orderBy('order', 'asc')->orderBy('id', 'asc')->limit($limit)->get();
    }

    public function findAll()
    {
        return EprPortal::orderBy('order', 'asc')->orderBy('id', 'asc')->get();
    }

    public function findById($id)
    {
        return EprPortal::find($id);
    }

    public function create($data)
    {
        return EprPortal::create($data);
    }

    public function update($data, $id)
    {
        $result = EprPortal::find($id);
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
        $result = EprPortal::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
