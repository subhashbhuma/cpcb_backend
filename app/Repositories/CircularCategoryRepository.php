<?php

namespace App\Repositories;

use App\Models\CircularCategory;

class CircularCategoryRepository
{

    public function findForPublic()
    {
        return CircularCategory::orderBy('name', 'ASC')->get();
    }

    public function findAll()
    {
        return CircularCategory::orderBy('name', 'ASC')->get();
    }

    public function findById($id)
    {
        return CircularCategory::find($id);
    }

    public function create($data)
    {
        return CircularCategory::create($data);
    }

    public function update($data, $id)
    {
        $result = CircularCategory::find($id);
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
        $result = CircularCategory::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
