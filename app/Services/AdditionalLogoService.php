<?php

namespace App\Services;

use App\DTO\AdditionalLogoDto;
use App\Repositories\AdditionalLogoRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class AdditionalLogoService
{
    use FileUploadTrait;
    private $additionalLogoRepository;

    public function __construct()
    {
        $this->additionalLogoRepository = new AdditionalLogoRepository();
    }

    public function findAll()
    {
        return $this->additionalLogoRepository->findAll();
    }

    public function findById($id)
    {
        return $this->additionalLogoRepository->findById($id);
    }

    public function create(AdditionalLogoDto $additionalLogoDto)
    {
        // Upload
        if ($additionalLogoDto->file_name) {
            $file = $this->uploadFile($additionalLogoDto->file_name, Config::get('file_paths')['ADDITIONAL_LOGO_IMAGE_PATH']);
            $additionalLogoDto->file_name = $file['file_name'];
        }

        $result = $this->additionalLogoRepository->create([
            'link' => $additionalLogoDto->link,
            'title' => $additionalLogoDto->title,
            'title_hi' => $additionalLogoDto->title_hi,
            'file_name' => $additionalLogoDto->file_name,
            'created_by' => $additionalLogoDto->created_by,
            'updated_by' => $additionalLogoDto->updated_by,
        ]);

        if (!$result) {
            return false;
        }

        return $result;
    }


    public function update(AdditionalLogoDto $additionalLogoDto, $id)
    {
        // Upload
        if ($additionalLogoDto->file_name) {
            $file = $this->uploadFile($additionalLogoDto->file_name, Config::get('file_paths')['ADDITIONAL_LOGO_IMAGE_PATH']);
            $additionalLogoDto->file_name = $file['file_name'];
        }

        $updateData = [
            'link' => $additionalLogoDto->link,
            'title' => $additionalLogoDto->title,
            'title_hi' => $additionalLogoDto->title_hi,
            'created_by' => $additionalLogoDto->created_by,
            'updated_by' => $additionalLogoDto->updated_by,
        ];

        if ($additionalLogoDto->file_name) {
            $updateData['file_name'] = $additionalLogoDto->file_name;
        }

        $result = $this->additionalLogoRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function delete($id)
    {
        return $this->additionalLogoRepository->delete($id);
    }

    public function approve(AdditionalLogoDto $additionalLogoDto, $id)
    {
        $updateData = [
            'is_approved' => $additionalLogoDto->is_approved,
            'remarks' => $additionalLogoDto->remarks,
            'updated_by' => $additionalLogoDto->updated_by,
        ];

        $result = $this->additionalLogoRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function publish(AdditionalLogoDto $additionalLogoDto, $id)
    {
        $updateData = [
            'is_approved' => $additionalLogoDto->is_approved,
            'is_published' => $additionalLogoDto->is_published,
            'remarks' => $additionalLogoDto->remarks,
            'updated_by' => $additionalLogoDto->updated_by,
        ];

        $result = $this->additionalLogoRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }
}
