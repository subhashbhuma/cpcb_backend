<?php

namespace App\Repositories;

use App\Models\PhotoGalleryFile;

class PhotoGalleryFileRepository
{
    public function findAll()
    {
        return PhotoGalleryFile::with('page')->get();
    }

    public function findById($id)
    {
        return PhotoGalleryFile::find($id);
    }

    public function bulkInsert($data)
    {
        return PhotoGalleryFile::insert($data);
    }

    public function create($data)
    {
        return PhotoGalleryFile::create($data);
    }

    public function update($data, $id)
    {
        $result = PhotoGalleryFile::find($id);
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
        $result = PhotoGalleryFile::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
