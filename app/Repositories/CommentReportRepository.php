<?php

namespace App\Repositories;

use App\Models\CommentReport;

class CommentReportRepository
{
    public function findForPublicWithPagination($perPage = 10)
    {
        return CommentReport::where('is_published', 1)->orderBy('published_date', 'DESC')->paginate($perPage);
    }

    public function findForPublic($limit = null)
    {
        if ($limit == null) {
            return CommentReport::where('is_published', 1)->orderBy('published_date', 'desc')->get();
        }
        return CommentReport::where('is_published', 1)->limit($limit)->orderBy('published_date', 'desc')->get();
    }

    public function findAll()
    {
        return CommentReport::orderBy('published_date', 'desc')->get();
    }

    public function findById($id)
    {
        return CommentReport::find($id);
    }

    public function create($data)
    {
        return CommentReport::create($data);
    }

    public function update($data, $id)
    {
        $result = CommentReport::find($id);
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
        $result = CommentReport::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
