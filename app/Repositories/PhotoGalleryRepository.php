<?php

namespace App\Repositories;

use App\Models\PhotoGallery;

class PhotoGalleryRepository
{
    public function findForPublic($limit = 10)
    {
        return PhotoGallery::with(['event'])->withCount('images')->where('is_published', 1)->limit($limit)->orderBy('id', 'desc')->get();
    }
    public function findForPublicByEventId($eventId, $limit)
    {
        return PhotoGallery::withCount('images')->where('gallery_event_id', $eventId)->where('is_published', 1)->limit($limit)->orderBy('id', 'desc')->get();
    }



    public function findAll()
    {
        return PhotoGallery::with(['images', 'event'])
            ->withCount('images')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function findById($id)
    {
        return PhotoGallery::find($id);
    }

    public function findBySlug($slug)
    {
        return PhotoGallery::where([
            'slug' => $slug
        ])->first();
    }

    public function create($data)
    {
        return PhotoGallery::create($data);
    }

    public function update($data, $id)
    {
        $result = PhotoGallery::find($id);
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
        $result = PhotoGallery::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }

    public function findByIdWithImages($id)
    {
        return PhotoGallery::with(['images'])->find($id);
    }
    public function getLastUpdatedOrCreatedAt()
    {
        return PhotoGallery::getLastUpdatedOrCreatedAt();
    }
}
