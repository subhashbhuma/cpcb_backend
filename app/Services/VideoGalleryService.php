<?php

namespace App\Services;

use App\Repositories\VideoGalleryRepository;
use App\DTO\VideoGalleryDto;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class VideoGalleryService
{
    use FileUploadTrait;
    private $videoGalleryRepository;

    public function __construct()
    {
        $this->videoGalleryRepository = new VideoGalleryRepository();
    }

    public function findForPublic($limit = 10)
    {
        return $this->videoGalleryRepository->findForPublic($limit);
    }

    public function findAll()
    {
        return $this->videoGalleryRepository->findAll();
    }

    public function findById($id)
    {
        return $this->videoGalleryRepository->findById($id);
    }


    public function create(VideoGalleryDto $videoGalleryDto)
    {
        // Upload
        if ($videoGalleryDto->thumbnail_image) {
            $file = $this->uploadFile($videoGalleryDto->thumbnail_image, Config::get('file_paths')['VIDEO_GALLERY_THUMBNAIL_IMAGE_PATH']);
            $videoGalleryDto->thumbnail_image = $file['file_name'];
        }

        if ($videoGalleryDto->file_name) {
            $file = $this->uploadFile($videoGalleryDto->file_name, Config::get('file_paths')['VIDEO_GALLERY_VIDEO_PATH']);
            $videoGalleryDto->file_name = $file['file_name'];
        }

        $videoGallery = $this->videoGalleryRepository->create([
            'gallery_event_id' => $videoGalleryDto->gallery_event_id,
            'thumbnail_image' => $videoGalleryDto->thumbnail_image,
            'type' => $videoGalleryDto->type,
            'file_name' => $videoGalleryDto->file_name,
            'youtube_embed_code' => $videoGalleryDto->youtube_embed_code,
            'url' => $videoGalleryDto->url,
            'title' => $videoGalleryDto->title,
            'title_hi' => $videoGalleryDto->title_hi,
            'description' => $videoGalleryDto->description,
            'description_hi' => $videoGalleryDto->description_hi,
            'date' => $videoGalleryDto->date,
            'created_by' => $videoGalleryDto->created_by,
            'updated_by' => $videoGalleryDto->updated_by,
        ]);

        if (!$videoGallery) {
            return false;
        }

        return $videoGallery;
    }

    public function update(VideoGalleryDto $videoGalleryDto, $id)
    {
        // Upload
        if ($videoGalleryDto->thumbnail_image) {
            $file = $this->uploadFile($videoGalleryDto->thumbnail_image, Config::get('file_paths')['VIDEO_GALLERY_THUMBNAIL_IMAGE_PATH']);
            $videoGalleryDto->thumbnail_image = $file['file_name'];
        }

        if ($videoGalleryDto->file_name) {
            $file = $this->uploadFile($videoGalleryDto->file_name, Config::get('file_paths')['VIDEO_GALLERY_VIDEO_PATH']);
            $videoGalleryDto->file_name = $file['file_name'];
        }

        $updateData = [
            'gallery_event_id' => $videoGalleryDto->gallery_event_id,
            'type' => $videoGalleryDto->type,
            'youtube_embed_code' => $videoGalleryDto->youtube_embed_code,
            'url' => $videoGalleryDto->url,
            'title' => $videoGalleryDto->title,
            'title_hi' => $videoGalleryDto->title_hi,
            'description' => $videoGalleryDto->description,
            'description_hi' => $videoGalleryDto->description_hi,
            'date' => $videoGalleryDto->date,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'updated_by' => $videoGalleryDto->updated_by,
        ];

        if ($videoGalleryDto->thumbnail_image) {
            $updateData['thumbnail_image'] = $videoGalleryDto->thumbnail_image;
        }

        if ($videoGalleryDto->file_name) {
            $updateData['file_name'] = $videoGalleryDto->file_name;
        }

        $videoGallery = $this->videoGalleryRepository->update($updateData, $id);

        if (!$videoGallery) {
            return false;
        }

        return $this->findById($id);
    }

    public function delete($id)
    {
        return $this->videoGalleryRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $updateData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->videoGalleryRepository->update($updateData, $id);
    }

    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null)
    {
        $updateData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'is_published' => $isPublished,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->videoGalleryRepository->update($updateData, $id);
    }

    public function findForPublicByEventId($eventId, $limit)
    {
        return $this->videoGalleryRepository->findForPublicByEventId($eventId, $limit);
    }

    public function getLastUpdatedOrCreatedAt()
    {
        return $this->videoGalleryRepository->getLastUpdatedOrCreatedAt();
    }

}
