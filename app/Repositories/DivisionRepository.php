<?php

namespace App\Repositories;

use App\Models\Division;

class DivisionRepository
{
    public function findForPublic($limit = 10)
    {
        return Division::where('is_published', 1)->limit($limit)->orderBy('id', 'desc')->get();
    }
    public function findPublished($is_new)
    {
        if ($is_new) {
            return Division::where(['is_published' => 1, 'is_approved' => 1, 'is_new' => 1])->orderBy('title', 'asc')->get();
        } else {
            return Division::where(['is_published' => 1, 'is_approved' => 1])->orderBy('title', 'asc')->get();
        }
    }

    public function findAll()
    {
        return Division::orderBy('id', 'desc')
            ->get();
    }
    public function findById($id)
    {
        return Division::find($id);
    }

    public function create($data)
    {
        return Division::create($data);
    }

    public function update($data, $id)
    {
        $result = Division::find($id);
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
        $result = Division::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
