<?php

namespace App\Services;

use App\DTO\CircularDto;
use App\Repositories\CircularRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class CircularService
{
    use FileUploadTrait;
    private $circularRepository;

    public function __construct()
    {
        $this->circularRepository = new CircularRepository();
    }

    public function findForPublicWithPagination($perPage = 10)
    {
        return $this->circularRepository->findForPublicWithPagination($perPage);
    }

    public function findByCategoryWithPagination($categoryId, $perPage = 10)
    {
        return $this->circularRepository->findByCategoryWithPagination($categoryId, $perPage);
    }

    public function findForPublic($limit = null)
    {
        return $this->circularRepository->findForPublic($limit);
    }

    public function findAll()
    {
        return $this->circularRepository->findAll();
    }
    public function fetchForDatatable($archiveStatus = null, $category = null)
    {
        return $this->circularRepository->fetchForDatatable($archiveStatus, $category);
    }

    public function findById($id)
    {
        return $this->circularRepository->findById($id);
    }

    public function create(CircularDto $circularDto)
    {
        if ($circularDto->file_name) {
            $uploaded = $this->uploadFile($circularDto->file_name, Config::get('file_paths')['CIRCULAR_FILE_EN_PATH']);
            $circularDto->file_name = $uploaded['file_name'];
        }

        // Upload file_name_hi if exists
        if ($circularDto->file_name_hi) {
            $uploadedHi = $this->uploadFile($circularDto->file_name_hi, Config::get('file_paths')['CIRCULAR_FILE_HI_PATH']);
            $circularDto->file_name_hi = $uploadedHi['file_name'];
        }

        $result = $this->circularRepository->create([
            'title' => $circularDto->title,
            'title_hi' => $circularDto->title_hi,
            'division_id' => $circularDto->division_id,
            'category' => $circularDto->category,
            'published_date' => $circularDto->published_date,
            'file_name' => $circularDto->file_name,
            'file_name_hi' => $circularDto->file_name_hi,
            'is_approved' => $circularDto->is_approved,
            'is_published' => $circularDto->is_published,
            'remarks' => $circularDto->remarks,
            'publish_remark' => $circularDto->publish_remark,
            'created_by' => $circularDto->created_by,
            'updated_by' => $circularDto->updated_by,
        ]);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function update(CircularDto $circularDto, $id)
    {
        // Upload file if type is "file"
        if ($circularDto->file_name) {
            $uploaded = $this->uploadFile($circularDto->file_name, Config::get('file_paths')['CIRCULAR_FILE_EN_PATH']);
            $circularDto->file_name = $uploaded['file_name'];
        }

        // Upload file_name_hi if exists
        if ($circularDto->file_name_hi) {
            $uploadedHi = $this->uploadFile($circularDto->file_name_hi, Config::get('file_paths')['CIRCULAR_FILE_HI_PATH']);
            $circularDto->file_name_hi = $uploadedHi['file_name'];
        }

        $updateData = [
            'title' => $circularDto->title,
            'title_hi' => $circularDto->title_hi,
            'division_id' => $circularDto->division_id,
            'published_date' => $circularDto->published_date,
            'category' => $circularDto->category,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'updated_by' => $circularDto->updated_by,
        ];

        if ($circularDto->file_name) {
            $updateData['file_name'] = $circularDto->file_name;
        }

        if ($circularDto->file_name_hi) {
            $updateData['file_name_hi'] = $circularDto->file_name_hi;
        }

        $result = $this->circularRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }


    public function delete($id)
    {
        return $this->circularRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $updatedData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->circularRepository->update($updatedData, $id);
    }

    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null)
    {
        $updatedData = [
            'is_published' => $isPublished,
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->circularRepository->update($updatedData, $id);
    }
}
