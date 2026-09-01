<?php

namespace App\Repositories;

use App\Models\PageFile;

class PageFileRepository
{
    public function findAll()
    {
        return PageFile::with('page')->get();
    }

    public function findById($id)
    {
        return PageFile::find($id);
    }

    public function bulkInsert($data)
    {
        return PageFile::insert($data);
    }

    public function create($data)
    {
        return PageFile::create($data);
    }

    public function update($data, $id)
    {
        $result = PageFile::find($id);
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
        $result = PageFile::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
