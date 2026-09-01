<?php

namespace App\Services;

use App\Repositories\PhotoGalleryFileRepository;
use App\DTO\PhotoGalleryFileDto;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class PhotoGalleryFileService
{
    use FileUploadTrait;
    private $photoGalleryFileRepository;

    public function __construct()
    {
        $this->photoGalleryFileRepository = new PhotoGalleryFileRepository();
    }

    public function findAll()
    {
        return $this->photoGalleryFileRepository->findAll();
    }

    public function findById($id)
    {
        return $this->photoGalleryFileRepository->findById($id);
    }

    public function create(PhotoGalleryFileDto $photoGalleryFileDto)
    {
        // PDF
        if ($photoGalleryFileDto->image_name) {
            $file = $this->uploadFile($photoGalleryFileDto->image_name, Config::get('file_paths')['PHOTO_GALLERY_IMAGES_PATH']);
            $photoGalleryFileDto->image_name = $file['file_name'];
        }

        $photoGallery = $this->photoGalleryFileRepository->create([
            'photo_gallery_id' => $photoGalleryFileDto->photo_gallery_id,
            'file_name' => $photoGalleryFileDto->image_name,
            'title' => $photoGalleryFileDto->title,
            'title_hi' => $photoGalleryFileDto->title_hi,
            'created_by' => $photoGalleryFileDto->created_by,
        ]);

        if (!$photoGallery) {
            return false;
        }

        return $photoGallery;
    }

    public function update(PhotoGalleryFileDto $photoGalleryFileDto, $id)
    {
        // PDF
        if ($photoGalleryFileDto->image_name) {
            $file = $this->uploadFile($photoGalleryFileDto->image_name, Config::get('file_paths')['PHOTO_GALLERY_IMAGES_PATH']);
            $photoGalleryFileDto->image_name = $file['file_name'];
        }

        $photoGallery = $this->photoGalleryFileRepository->update([
            'photo_gallery_id' => $photoGalleryFileDto->photo_gallery_id,
            'file_name' => $photoGalleryFileDto->image_name,
            'title' => $photoGalleryFileDto->title,
            'title_hi' => $photoGalleryFileDto->title_hi,
            'created_by' => $photoGalleryFileDto->created_by,
            'updated_by' => $photoGalleryFileDto->updated_by,
        ], $id);

        if (!$photoGallery) {
            return false;
        }

        return $photoGallery;
    }

    public function delete($id)
    {
        return $this->photoGalleryFileRepository->delete($id);
    }
}
