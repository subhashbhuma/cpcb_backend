<?php

namespace App\Repositories;

use App\Models\VideoGallery;

class VideoGalleryRepository
{
    public function findForPublic($limit = 10)
    {
        return VideoGallery::where('is_published', 1)->limit($limit)->orderBy('id', 'desc')->get();
    }

    public function findAll()
    {
        return VideoGallery::orderBy('id', 'desc')->get();
    }

    public function findById($id)
    {
        return VideoGallery::find($id);
    }

    public function findBySlug($slug)
    {
        return VideoGallery::where([
            'slug' => $slug
        ])->first();
    }

    public function create($data)
    {
        return VideoGallery::create($data);
    }

    public function update($data, $id)
    {
        $result = VideoGallery::find($id);
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
        $result = VideoGallery::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }

    public function findForPublicByEventId($eventId, $limit)
    {
        return VideoGallery::where('gallery_event_id', $eventId)->where('is_published', 1)->limit($limit)->orderBy('id', 'desc')->get();
    }

    public function getLastUpdatedOrCreatedAt()
    {
        return VideoGallery::getLastUpdatedOrCreatedAt();
    }
}
