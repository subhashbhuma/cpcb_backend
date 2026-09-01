<?php

namespace App\Services;

use App\DTO\GalleryEventDto;
use App\Repositories\GalleryEventRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class GalleryEventService
{
    use FileUploadTrait;
    private $galleryEventRepository;

    public function __construct()
    {
        $this->galleryEventRepository = new GalleryEventRepository();
    }

    public function findForPublic($limit = null)
    {
        return $this->galleryEventRepository->findForPublic($limit);
    }

    public function findAll()
    {
        return $this->galleryEventRepository->findAll();
    }

    public function findById($id)
    {
        return $this->galleryEventRepository->findById($id);
    }

    public function create(GalleryEventDto $galleryEventDto)
    {
        // Upload
        if ($galleryEventDto->featured_image) {
            $file = $this->uploadFile($galleryEventDto->featured_image, Config::get('file_paths')['GALLERY_EVENT_FEATURED_IMAGE_PATH']);
            $galleryEventDto->featured_image = $file['file_name'];
        }

        $photoGallery = $this->galleryEventRepository->create([
            'featured_image' => $galleryEventDto->featured_image,
            'title' => $galleryEventDto->title,
            'title_hi' => $galleryEventDto->title_hi,
            'created_by' => $galleryEventDto->created_by,
        ]);

        if (!$photoGallery) {
            return false;
        }

        return $photoGallery;
    }

    public function update(galleryEventDto $galleryEventDto, $id)
    {
        // Upload
        if ($galleryEventDto->featured_image) {
            $file = $this->uploadFile($galleryEventDto->featured_image, Config::get('file_paths')['GALLERY_EVENT_FEATURED_IMAGE_PATH']);
            $galleryEventDto->featured_image = $file['file_name'];
        }

        $updateData = [
            'title' => $galleryEventDto->title,
            'title_hi' => $galleryEventDto->title_hi,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'updated_by' => $galleryEventDto->updated_by,
        ];

        if ($galleryEventDto->featured_image) {
            $updateData['featured_image'] = $galleryEventDto->featured_image;
        }

        $photoGallery = $this->galleryEventRepository->update($updateData, $id);

        if (!$photoGallery) {
            return false;
        }

        return $this->findById($id);
    }

    public function delete($id)
    {
        return $this->galleryEventRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $updateData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->galleryEventRepository->update($updateData, $id);
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

        return $this->galleryEventRepository->update($updateData, $id);
    }
}
