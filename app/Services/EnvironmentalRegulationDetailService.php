<?php

namespace App\Services;

use App\Repositories\EnvironmentalRegulationDetailRepository;
use App\DTO\EnvironmentalRegulationDetailDto;
;

use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class EnvironmentalRegulationDetailService
{
    use FileUploadTrait;

    private EnvironmentalRegulationDetailRepository $repository;

    public function __construct()
    {
        $this->repository = new EnvironmentalRegulationDetailRepository();
    }

    /* -----------------------------
     | Fetch
     |-----------------------------*/

    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function findAllForPublic()
    {
        return $this->repository->findAllForPublic();
    }

    public function findById($id)
    {
        return $this->repository->findById($id);
    }


    public function findByRegulationIdForPublic($regulationId)
    {
        return $this->repository->findByRegulationIdForPublic($regulationId);
    }

    public function findByUrlForPublic($url)
    {
        return $this->repository->findByUrlForPublic($url);
    }



    public function findForPublicByRegulationId($regulationId, $limit)
    {
        return $this->repository->findForPublicByRegulationId($regulationId, $limit);
    }


    /* -----------------------------
     | Create
     |-----------------------------*/

    public function create(EnvironmentalRegulationDetailDto $dto)
    {
        if ($dto->type === 'FILE' && $dto->file_name) {
            $uploaded = $this->uploadFile($dto->file_name, Config::get('file_paths')['ENV_REGULATION_DETAIL_FILE_EN_PATH']);
            $dto->file_name = $uploaded['file_name'];
        }

        if ($dto->type === 'FILE' && $dto->file_name_hi) {
            $uploadedHi = $this->uploadFile($dto->file_name_hi, Config::get('file_paths')['ENV_REGULATION_DETAIL_FILE_HI_PATH']);
            $dto->file_name_hi = $uploadedHi['file_name'];
        }

        return $this->repository->create([
            'environmental_regulation_id' => $dto->environmental_regulation_id,
            'parent_id' => $dto->parent_id,
            'order' => $dto->order,
            'type' => $dto->type,
            'url' => $dto->type === 'URL' ? $dto->url : null,
            'file_name' => $dto->type === 'FILE' ? $dto->file_name : null,
            'file_name_hi' => $dto->type === 'FILE' ? $dto->file_name_hi : null,
            'title' => $dto->title,
            'title_hi' => $dto->title_hi,
            'is_approved' => $dto->is_approved,
            'is_published' => $dto->is_published,
            'remarks' => $dto->remarks,
            'publish_remark' => $dto->publish_remark,
            'created_by' => $dto->created_by,
        ]);
    }

    /* -----------------------------
     | Update
     |-----------------------------*/

    public function update(EnvironmentalRegulationDetailDto $dto, $id)
    {
        if ($dto->type === 'FILE' && $dto->file_name !== null) {
            $uploaded = $this->uploadFile($dto->file_name, Config::get('file_paths')['ENV_REGULATION_DETAIL_FILE_EN_PATH']);
            $dto->file_name = $uploaded['file_name'];
        }

        if ($dto->type === 'FILE' && $dto->file_name_hi !== null) {
            $uploadedHi = $this->uploadFile($dto->file_name_hi, Config::get('file_paths')['ENV_REGULATION_DETAIL_FILE_HI_PATH']);
            $dto->file_name_hi = $uploadedHi['file_name'];
        }
        $updateData = [
            'environmental_regulation_id' => $dto->environmental_regulation_id,
            'parent_id' => $dto->parent_id,
            'order' => $dto->order,
            'type' => $dto->type,
            'url' => $dto->type === 'URL' ? $dto->url : null,
            'title' => $dto->title,
            'title_hi' => $dto->title_hi,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'updated_by' => $dto->updated_by,
        ];

        if ($dto->type === 'FILE') {
            if ($dto->file_name) {
                $updateData['file_name'] = $dto->file_name;
            }

            if ($dto->file_name_hi) {
                $updateData['file_name_hi'] = $dto->file_name_hi;
            }
        } else {
            $updateData['file_name'] = null;
            $updateData['file_name_hi'] = null;
        }

        $result = $this->repository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $this->findById($id);
    }

    /* -----------------------------
     | Approval
     |-----------------------------*/

    public function approve(EnvironmentalRegulationDetailDto $dto, $id)
    {
        return $this->repository->update([
            'is_approved' => $dto->is_approved,
            'remarks' => $dto->remarks,
            'publish_remark' => $dto->publish_remark,
            'updated_by' => $dto->updated_by,
        ], $id);
    }

    public function publish(EnvironmentalRegulationDetailDto $dto, $id)
    {
        return $this->repository->update([
            'is_approved' => $dto->is_approved,
            'is_published' => $dto->is_published,
            'remarks' => $dto->remarks,
            'publish_remark' => $dto->publish_remark,
            'updated_by' => $dto->updated_by,
        ], $id);
    }

    /* -----------------------------
     | Delete
     |-----------------------------*/

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}
