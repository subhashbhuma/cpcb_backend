<?php

namespace App\Services;

use App\DTO\DirectionDto;
use App\Repositories\DirectionRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class DirectionService
{
    use FileUploadTrait;
    private $directionRepository;

    public function __construct()
    {
        $this->directionRepository = new DirectionRepository();
    }

    public function findAll()
    {
        return $this->directionRepository->findAll();
    }
    public function fetchForDatatable()
    {
        return $this->directionRepository->fetchForDatatable();
    }

    public function fetchAllForPublicDataTable($filters = [], $type = 'latest')
    {
        return $this->directionRepository->fetchAllForPublicDataTable($filters, $type);
    }

    public function findById($id)
    {
        return $this->directionRepository->findById($id);
    }

    public function create(DirectionDto $directionDto)
    {
        if ($directionDto->file_name) {
            $uploaded = $this->uploadFile($directionDto->file_name, Config::get('file_paths')['DIRECTION_FILE_EN_PATH']);
            $directionDto->file_name = $uploaded['file_name'];
        }

        if ($directionDto->file_name_hi) {
            $uploadedHi = $this->uploadFile($directionDto->file_name_hi, Config::get('file_paths')['DIRECTION_FILE_HI_PATH']);
            $directionDto->file_name_hi = $uploadedHi['file_name'];
        }

        return $this->directionRepository->create($directionDto->toArray());
    }

    public function update(DirectionDto $directionDto, $id)
    {
        if ($directionDto->file_name) {
            $uploaded = $this->uploadFile($directionDto->file_name, Config::get('file_paths')['DIRECTION_FILE_EN_PATH']);
            $directionDto->file_name = $uploaded['file_name'];
        }

        if ($directionDto->file_name_hi) {
            $uploadedHi = $this->uploadFile($directionDto->file_name_hi, Config::get('file_paths')['DIRECTION_FILE_HI_PATH']);
            $directionDto->file_name_hi = $uploadedHi['file_name'];
        }

        $data = $directionDto->toArray();
        unset($data['created_by']);

        if (!$directionDto->file_name) {
            unset($data['file_name']);
        }

        if (!$directionDto->file_name_hi) {
            unset($data['file_name_hi']);
        }

        // Reset on update
        $data['is_approved'] = 0;
        $data['is_published'] = 0;
        $data['remarks'] = null;
        $data['publish_remark'] = null;

        return $this->directionRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->directionRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $data = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->directionRepository->update($data, $id);
    }

    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null)
    {
        $data = [
            'is_approved' => $isApproved,
            'is_published' => $isPublished,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->directionRepository->update($data, $id);
    }

    public function findForPublicWithPagination($perPage = 10)
    {
        return $this->directionRepository->findForPublicWithPagination($perPage);
    }

    public function findForPublic($limit = null)
    {
        return $this->directionRepository->findForPublic($limit);
    }
}
