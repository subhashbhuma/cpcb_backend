<?php

namespace App\Services;

use App\Repositories\PhotoGalleryRepository;
use App\DTO\PhotoGalleryDto;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class PhotoGalleryService
{
    use FileUploadTrait;
    private $photoGalleryRepository;

    public function __construct()
    {
        $this->photoGalleryRepository = new PhotoGalleryRepository();
    }

    public function findForPublic($limit = 10)
    {
        return $this->photoGalleryRepository->findForPublic($limit);
    }

    public function findAll()
    {
        return $this->photoGalleryRepository->findAll();
    }

    public function findById($id)
    {
        return $this->photoGalleryRepository->findById($id);
    }

    public function create(PhotoGalleryDto $photoGalleryDto)
    {
        // Upload
        if ($photoGalleryDto->featured_image) {
            $file = $this->uploadFile($photoGalleryDto->featured_image, Config::get('file_paths')['PHOTO_GALLERY_FEATURED_IMAGE_PATH']);
            $photoGalleryDto->featured_image = $file['file_name'];
        }

        $photoGallery = $this->photoGalleryRepository->create([
            'gallery_event_id' => $photoGalleryDto->gallery_event_id,
            'featured_image' => $photoGalleryDto->featured_image,
            'title' => $photoGalleryDto->title,
            'title_hi' => $photoGalleryDto->title_hi,
            'description' => $photoGalleryDto->description,
            'description_hi' => $photoGalleryDto->description_hi,
            'date' => $photoGalleryDto->date,
            'created_by' => $photoGalleryDto->created_by,
        ]);

        if (!$photoGallery) {
            return false;
        }

        return $photoGallery;
    }

    public function update(PhotoGalleryDto $photoGalleryDto, $id)
    {
        // Upload
        if ($photoGalleryDto->featured_image) {
            $file = $this->uploadFile($photoGalleryDto->featured_image, Config::get('file_paths')['PHOTO_GALLERY_FEATURED_IMAGE_PATH']);
            $photoGalleryDto->featured_image = $file['file_name'];
        }

        $updateData = [
            'gallery_event_id' => $photoGalleryDto->gallery_event_id,
            'title' => $photoGalleryDto->title,
            'title_hi' => $photoGalleryDto->title_hi,
            'description' => $photoGalleryDto->description,
            'description_hi' => $photoGalleryDto->description_hi,
            'date' => $photoGalleryDto->date,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'updated_by' => $photoGalleryDto->updated_by,
        ];

        if ($photoGalleryDto->featured_image) {
            $updateData['featured_image'] = $photoGalleryDto->featured_image;
        }

        $photoGallery = $this->photoGalleryRepository->update($updateData, $id);

        if (!$photoGallery) {
            return false;
        }

        return $this->findById($id);
    }

    public function delete($id)
    {
        return $this->photoGalleryRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $updateData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->photoGalleryRepository->update($updateData, $id);
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

        return $this->photoGalleryRepository->update($updateData, $id);
    }

    public function findByIdWithImages($id)
    {
        return $this->photoGalleryRepository->findByIdWithImages($id);
    }

    public function findForPublicByEventId($eventId, $limit)
    {
        return $this->photoGalleryRepository->findForPublicByEventId($eventId, $limit);
    }


    public function getLastUpdatedOrCreatedAt()
    {
        return $this->photoGalleryRepository->getLastUpdatedOrCreatedAt();
    }
}
