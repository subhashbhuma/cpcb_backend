<?php

namespace App\Repositories;

use App\Models\Media;

class MediaRepository
{
    public function findAllWithPagination($perPage = 12)
    {
        return Media::orderBy('id', 'desc')->paginate($perPage);
    }

    public function findAll()
    {
        return Media::orderBy('id', 'desc')->get();
    }

    public function findById($id)
    {
        return Media::find($id);
    }

    public function create($data)
    {
        return Media::create($data);
    }

    public function update($data, $id)
    {
        $result = Media::find($id);
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
        $result = Media::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
