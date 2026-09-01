<?php

namespace App\Services;

use App\DTO\LabsCategoryDto;
use App\DTO\QuickLinkDto;
use App\DTO\RegionalDirectoriesDto;
use App\Repositories\labCatRepository;
use App\Repositories\LaboratoriesCategoryRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class LaboratoriesCategoryService
{
    use FileUploadTrait;
    private $labCatRepository;

    public function __construct()
    {
        $this->labCatRepository = new LaboratoriesCategoryRepository();
    }

    public function findForPublic($limit = 10)
    {
        return $this->labCatRepository->findForPublic();
    }
    public function findAllForPublic()
    {
        return $this->labCatRepository->findAllForPublic();
    }

    public function findAll()
    {
        return $this->labCatRepository->findAll();
    }

    public function findById($id)
    {
        return $this->labCatRepository->findById($id);
    }


    public function create(LabsCategoryDto $labsCategoryDto)
    {
        if ($labsCategoryDto->featured_image) {
            $file = $this->uploadFile($labsCategoryDto->featured_image, Config::get('file_paths')['LABORATORIES_CATEGORY_FEATURED_IMAGE_PATH']);
            $labsCategoryDto->featured_image = $file['file_name'];
        }

        $result = $this->labCatRepository->create([
            'title' => $labsCategoryDto->title,
            'title_hi' => $labsCategoryDto->title_hi,
            'slogan' => $labsCategoryDto->slogan,
            'slogan_hi' => $labsCategoryDto->slogan_hi,
            'description' => $labsCategoryDto->description,
            'description_hi' => $labsCategoryDto->description_hi,
            'featured_image' => $labsCategoryDto->featured_image,
            'permission_group' => $labsCategoryDto->permission_group,
            'is_approved' => $labsCategoryDto->is_approved,
            'is_published' => $labsCategoryDto->is_published,
            'remarks' => $labsCategoryDto->remarks,
            'created_by' => $labsCategoryDto->created_by,
            'updated_by' => $labsCategoryDto->updated_by,

        ]);

        if (!$result) {
            return false;
        }

        return $result;
    }


    public function update(LabsCategoryDto $labsCategoryDto, $id)
    {
        if ($labsCategoryDto->featured_image) {
            $file = $this->uploadFile($labsCategoryDto->featured_image, Config::get('file_paths')['LABORATORIES_CATEGORY_FEATURED_IMAGE_PATH']);
            $labsCategoryDto->featured_image = $file['file_name'];
        }

        $updateData = [
            'title' => $labsCategoryDto->title,
            'title_hi' => $labsCategoryDto->title_hi,
            'slogan' => $labsCategoryDto->slogan,
            'slogan_hi' => $labsCategoryDto->slogan_hi,
            'description' => $labsCategoryDto->description,
            'description_hi' => $labsCategoryDto->description_hi,
            'featured_image' => $labsCategoryDto->featured_image,
            'permission_group' => $labsCategoryDto->permission_group,
            'is_approved' => $labsCategoryDto->is_approved,
            'is_published' => $labsCategoryDto->is_published,
            'remarks' => $labsCategoryDto->remarks,
            'created_by' => $labsCategoryDto->created_by,
            'updated_by' => $labsCategoryDto->updated_by,
        ];

        $result = $this->labCatRepository->update($updateData, $id);

        if (!$result) {
            return $result;
        }

        return $result;
    }

    public function delete($id)
    {
        return $this->labCatRepository->delete($id);
    }

    public function approve(LabsCategoryDto $labsCategoryDto, $id)
    {
        $updateData = [
            'is_approved' => $labsCategoryDto->is_approved,
            'remarks' => $labsCategoryDto->remarks,
            'updated_by' => $labsCategoryDto->updated_by,
        ];

        $result = $this->labCatRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function publish(LabsCategoryDto $labsCategoryDto, $id)
    {
        $updateData = [
            'is_approved' => $labsCategoryDto->is_approved,
            'is_published' => $labsCategoryDto->is_published,
            'remarks' => $labsCategoryDto->remarks,
            'updated_by' => $labsCategoryDto->updated_by,
        ];

        $result = $this->labCatRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }
}
