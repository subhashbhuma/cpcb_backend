<?php

namespace App\Services;

use App\DTO\MediaDto;
use App\Repositories\MediaRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class MediaService
{
    use FileUploadTrait;
    private $mediaRepository;

    public function __construct()
    {
        $this->mediaRepository = new MediaRepository();
    }

    public function findAllWithPagination($perPage = 12)
    {
        return $this->mediaRepository->findAllWithPagination($perPage);
    }

    public function findAll()
    {
        return $this->mediaRepository->findAll();
    }

    public function findById($id)
    {
        return $this->mediaRepository->findById($id);
    }

    public function create(MediaDto $mediaDto)
    {
        // Upload
        if ($mediaDto->file_name) {
            $file = $this->uploadFile($mediaDto->file_name, Config::get('file_paths')['MEDIA_IMAGE_PATH']);
            $mediaDto->file_name = $file['file_name'];
            $mediaDto->original_name = $file['original_name'];
            $mediaDto->mime_type = $file['mime_type'];
            $mediaDto->size = $file['size'];
        }

        $result = $this->mediaRepository->create([
            'file_name' => $mediaDto->file_name,
            'original_name' => $mediaDto->original_name,
            'mime_type' => $mediaDto->mime_type,
            'size' => $mediaDto->size,
            'alt_text' => $mediaDto->alt_text,
            'alt_text_hi' => $mediaDto->alt_text_hi,
            'created_by' => $mediaDto->created_by
        ]);

        if (!$result) {
            return false;
        }

        return $result;
    }


    public function update(MediaDto $mediaDto, $id)
    {
        // Upload
        if ($mediaDto->file_name) {
            $file = $this->uploadFile($mediaDto->file_name, Config::get('file_paths')['MEDIA_IMAGE_PATH']);
            $mediaDto->file_name = $file['file_name'];
            $mediaDto->original_name = $file['original_name'];
            $mediaDto->mime_type = $file['mime_type'];
            $mediaDto->size = $file['size'];
        }

        $updateData = [
            'alt_text' => $mediaDto->alt_text,
            'alt_text_hi' => $mediaDto->alt_text_hi,
            'created_by' => $mediaDto->created_by,
            'updated_by' => $mediaDto->updated_by,
        ];

        if ($mediaDto->file_name) {
            $updateData['file_name'] = $mediaDto->file_name;
            $updateData['original_name'] = $mediaDto->original_name;
            $updateData['mime_type'] = $mediaDto->mime_type;
            $updateData['size'] = $mediaDto->size;
        }

        $result = $this->mediaRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function delete($id)
    {
        return $this->mediaRepository->delete($id);
    }

    public function approve(MediaDto $mediaDto, $id)
    {
        $updateData = [
            'is_approved' => $mediaDto->is_approved,
            'remarks' => $mediaDto->remarks,
            'updated_by' => $mediaDto->updated_by,
        ];

        $result = $this->mediaRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function publish(MediaDto $mediaDto, $id)
    {
        $updateData = [
            'is_approved' => $mediaDto->is_approved,
            'is_published' => $mediaDto->is_published,
            'remarks' => $mediaDto->remarks,
            'updated_by' => $mediaDto->updated_by,
        ];

        $result = $this->mediaRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }
}
