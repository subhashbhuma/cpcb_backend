<?php

namespace App\Repositories;

use App\Models\Portal;

class PortalRepository
{
    public function findForPublic($limit=null)
    {
        return Portal::where('is_published', 1)->orderBy('id', 'desc')->limit($limit)->get();
    }

    public function findAll()
    {
        return Portal::get();
    }

    public function findById($id)
    {
        return Portal::find($id);
    }

    public function create($data)
    {
        return Portal::create($data);
    }

    public function update($data, $id)
    {
        $result = Portal::find($id);
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
        $result = Portal::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
