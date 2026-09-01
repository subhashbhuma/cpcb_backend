<?php

namespace App\Repositories;

use App\Models\Publication;

class PublicationRepository
{
    public function findForPublic($limit = 10)
    {
        return Publication::where('is_published', 1)->with('publication_categories')->limit($limit)->orderBy('created_at', 'desc')->get();
    }

    public function findByCategoryForPublic($categoryId)
    {
        return Publication::where('category_id', $categoryId)
            ->where('is_published', 1)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findAll()
    {
        return Publication::with('category')->get();
    }
    public function findById($id)
    {
        return Publication::find($id);
    }

    public function create($data)
    {
        return Publication::create($data);
    }

    public function update($data, $id)
    {
        $result = Publication::find($id);
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
        $result = Publication::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
