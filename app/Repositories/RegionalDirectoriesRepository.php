<?php

namespace App\Repositories;

use App\Models\RegionalDirectories;

class RegionalDirectoriesRepository
{
    public function findForPublic($limit = 10)
    {
        return RegionalDirectories::where('is_published', 1)->limit($limit)->orderBy('id', 'desc')->get();
    }

    public function findAllForPublic()
    {
        return RegionalDirectories::where('is_published', 1)->orderBy('id', 'asc')->get();
    }

    public function findAll()
    {
        return RegionalDirectories::get();
    }

    public function findById($id)
    {
        return RegionalDirectories::find($id);
    }

    public function findByZone($zone)
    {
        return RegionalDirectories::where('zone', $zone)->get();
    }

    public function create($data)
    {
        return RegionalDirectories::create($data);
    }

    public function update($data, $id)
    {
        $result = RegionalDirectories::find($id);
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
        $result = RegionalDirectories::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
