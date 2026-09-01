<?php

namespace App\Services;

use App\DTO\PageCategoryDto;
use App\Repositories\PageCategoryRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class PageCategoryService
{
    use FileUploadTrait;
    private $pageCategoryRepository;

    public function __construct()
    {
        $this->pageCategoryRepository = new PageCategoryRepository();
    }

    public function findForPublic($limit = null)
    {
        return $this->pageCategoryRepository->findForPublic($limit);
    }

    public function findAll()
    {
        return $this->pageCategoryRepository->findAll();
    }

    public function findById($id)
    {
        return $this->pageCategoryRepository->findById($id);
    }

    public function fetchByIdForPublic($id)
    {
        return $this->pageCategoryRepository->fetchByIdForPublic($id);
    }

    public function create(PageCategoryDto $pageCategoryDto)
    {
        if ($pageCategoryDto->file_name) {
            $uploaded = $this->uploadFile($pageCategoryDto->file_name, Config::get('file_paths')['PAGE_CATEGORY_FILE_EN_PATH']);
            $pageCategoryDto->file_name = $uploaded['file_name'];
        }

        if ($pageCategoryDto->file_name_hi) {
            $uploadedHi = $this->uploadFile($pageCategoryDto->file_name_hi, Config::get('file_paths')['PAGE_CATEGORY_FILE_HI_PATH']);
            $pageCategoryDto->file_name_hi = $uploadedHi['file_name'];
        }

        return $this->pageCategoryRepository->create([
            'title' => $pageCategoryDto->title,
            'title_hi' => $pageCategoryDto->title_hi,
            'file_name' => $pageCategoryDto->file_name,
            'file_name_hi' => $pageCategoryDto->file_name_hi,
            'is_approved' => $pageCategoryDto->is_approved,
            'is_published' => $pageCategoryDto->is_published,
            'remarks' => $pageCategoryDto->remarks,
            'created_by' => $pageCategoryDto->created_by,
            'created_at' => $pageCategoryDto->created_at,
            'updated_by' => $pageCategoryDto->updated_by,
            'updated_at' => $pageCategoryDto->updated_at,
        ]);
    }

    public function update(PageCategoryDto $pageCategoryDto, $id)
    {
        if ($pageCategoryDto->file_name) {
            $uploaded = $this->uploadFile($pageCategoryDto->file_name, Config::get('file_paths')['PAGE_CATEGORY_FILE_EN_PATH']);
            $pageCategoryDto->file_name = $uploaded['file_name'];
        }

        if ($pageCategoryDto->file_name_hi) {
            $uploadedHi = $this->uploadFile($pageCategoryDto->file_name_hi, Config::get('file_paths')['PAGE_CATEGORY_FILE_HI_PATH']);
            $pageCategoryDto->file_name_hi = $uploadedHi['file_name'];
        }

        $data = [
            'title' => $pageCategoryDto->title,
            'title_hi' => $pageCategoryDto->title_hi,
            'is_approved' => $pageCategoryDto->is_approved,
            'is_published' => $pageCategoryDto->is_published,
            'remarks' => $pageCategoryDto->remarks,
            'created_by' => $pageCategoryDto->created_by,
            'created_at' => $pageCategoryDto->created_at,
            'updated_by' => $pageCategoryDto->updated_by,
            'updated_at' => $pageCategoryDto->updated_at,
        ];

        if ($pageCategoryDto->file_name) {
            $data['file_name'] = $pageCategoryDto->file_name;
        }

        if ($pageCategoryDto->file_name_hi) {
            $data['file_name_hi'] = $pageCategoryDto->file_name_hi;
        }

        return $this->pageCategoryRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->pageCategoryRepository->delete($id);
    }

    public function approve(PageCategoryDto $pageCategoryDto, $id)
    {
        $updatedData = [
            'is_approved' => $pageCategoryDto->is_approved,
            'remarks' => $pageCategoryDto->remarks,
            'updated_by' => $pageCategoryDto->updated_by,
            'updated_at' => $pageCategoryDto->updated_at
        ];
        $result = $this->pageCategoryRepository->update($updatedData, $id);
        if (!$result) {
            return false;
        }
        return $result;
    }

    public function publish(PageCategoryDto $pageCategoryDto, $id)
    {
        // When publishing, we also ensure it is approved if not already
        $updatedData = [
            'is_published' => $pageCategoryDto->is_published,
            'is_approved' => $pageCategoryDto->is_approved,
            'remarks' => $pageCategoryDto->remarks,
            'updated_by' => $pageCategoryDto->updated_by,
            'updated_at' => $pageCategoryDto->updated_at
        ];
        $result = $this->pageCategoryRepository->update($updatedData, $id);
        if (!$result) {
            return false;
        }
        return $result;
    }
}
