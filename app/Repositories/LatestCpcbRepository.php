<?php

namespace App\Repositories;

use App\Models\LatestCpcb;

class LatestCpcbRepository
{
    public function findForPublicWithPagination($perPage = 10)
    {
        return LatestCpcb::where('is_published', 1)->orderBy('publish_date', 'desc')->paginate($perPage);
    }

    public function findForPublic($limit = null)
    {
        if ($limit) {
            return LatestCpcb::where('is_published', 1)->limit($limit)->orderBy('publish_date', 'desc')->get();
        }
        return LatestCpcb::where('is_published', 1)->orderBy('publish_date', 'desc')->get();
    }

    public function findAll()
    {
        return LatestCpcb::with('division')->orderBy('id', 'DESC');
    }

    public function findById($id)
    {
        return LatestCpcb::find($id);
    }

    public function create($data)
    {
        return LatestCpcb::create($data);
    }

    public function update($data, $id)
    {
        $result = LatestCpcb::find($id);
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
        $result = LatestCpcb::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
