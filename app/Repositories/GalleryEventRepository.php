<?php

namespace App\Repositories;

use App\Models\GalleryEvent;

class GalleryEventRepository
{
    public function findForPublic($limit = null)
    {
        return GalleryEvent::withCount(['images', 'videoGalleries', 'photoGalleries'])->where('is_published', 1)->limit($limit ?? 100)->orderBy('id', 'desc')->get();
    }

    public function findAll()
    {
        return GalleryEvent::orderBy('id', 'desc')
            ->get();
    }
    public function findById($id)
    {
        return GalleryEvent::find($id);
    }

    public function create($data)
    {
        return GalleryEvent::create($data);
    }

    public function update($data, $id)
    {
        $result = GalleryEvent::find($id);
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
        $result = GalleryEvent::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
