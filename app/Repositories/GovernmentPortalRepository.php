<?php

namespace App\Repositories;

use App\Models\GovernmentPortal;

class GovernmentPortalRepository
{
    public function findForPublic()
    {
        return GovernmentPortal::where('is_published', 1)->orderBy('id', 'desc')->get();
    }

    public function findAll()
    {
        return GovernmentPortal::get();
    }

    public function findById($id)
    {
        return GovernmentPortal::find($id);
    }

    public function create($data)
    {
        return GovernmentPortal::create($data);
    }

    public function update($data, $id)
    {
        $result = GovernmentPortal::find($id);
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
        $result = GovernmentPortal::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
