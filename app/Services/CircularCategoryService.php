<?php

namespace App\Services;

use App\DTO\CircularCategoryDto;
use App\Repositories\CircularCategoryRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class CircularCategoryService
{
    use FileUploadTrait;
    private $circularCategoryRepository;

    public function __construct()
    {
        $this->circularCategoryRepository = new CircularCategoryRepository();
    }

    public function findForPublic()
    {
        return $this->circularCategoryRepository->findForPublic();
    }

    public function findAll()
    {
        return $this->circularCategoryRepository->findAll();
    }

    public function findById($id)
    {
        return $this->circularCategoryRepository->findById($id);
    }

    public function create(CircularCategoryDto $circularCategoryDto)
    {

        $result = $this->circularCategoryRepository->create([
            'name' => $circularCategoryDto->name,
            'name_hi' => $circularCategoryDto->name_hi,
            'created_by' => $circularCategoryDto->created_by,
            'updated_by' => $circularCategoryDto->updated_by,
        ]);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function update(CircularCategoryDto $circularCategoryDto, $id)
    {
        $updateData = [
            'name' => $circularCategoryDto->name,
            'name_hi' => $circularCategoryDto->name_hi,
            'updated_by' => $circularCategoryDto->updated_by,
        ];

        $result = $this->circularCategoryRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }


    public function delete($id)
    {
        return $this->circularCategoryRepository->delete($id);
    }
}
