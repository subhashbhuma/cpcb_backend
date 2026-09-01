<?php

namespace App\Repositories;

use App\Models\QuickLink;

class QuickLinkRepository
{
    public function findForPublic($limit = 10)
    {
        return QuickLink::where('is_published', 1)->limit($limit)->orderBy('id', 'desc')->get();
    }

    public function findAllForPublic()
    {
        return QuickLink::where('is_published', 1)->orderBy('id', 'asc')->get();
    }

    public function findAll()
    {
        return QuickLink::get();
    }

    public function findById($id)
    {
        return QuickLink::find($id);
    }

    public function create($data)
    {
        return QuickLink::create($data);
    }

    public function update($data, $id)
    {
        $result = QuickLink::find($id);
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
        $result = QuickLink::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
