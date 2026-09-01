<?php

namespace App\Repositories;

use App\Models\TenderCorrigendum;

class TenderCorrigendumRepository
{
    public function findAll()
    {
        return TenderCorrigendum::with('page')->get();
    }

    public function findById($id)
    {
        return TenderCorrigendum::find($id);
    }

    public function bulkInsert($data)
    {
        return TenderCorrigendum::insert($data);
    }

    public function create($data)
    {
        return TenderCorrigendum::create($data);
    }

    public function update($data, $id)
    {
        $result = TenderCorrigendum::find($id);
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
        $result = TenderCorrigendum::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
