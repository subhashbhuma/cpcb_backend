<?php

namespace App\Repositories;

use App\Models\PageCategory;

class PageCategoryRepository
{
    public function findForPublic($limit = 10)
    {
        return PageCategory::where('is_published', 1)->limit($limit)->orderBy('created_at', 'desc')->get();
    }

    public function findAll()
    {
        return PageCategory::orderBy('id', 'DESC')->get();
    }

    public function findById($id)
    {
        return PageCategory::find($id);
    }

    public function fetchByIdForPublic($id)
    {
        return PageCategory::find($id);
    }

    public function create($data)
    {
        return PageCategory::create($data);
    }

    public function update($data, $id)
    {
        $result = PageCategory::find($id);
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
        $result = PageCategory::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
