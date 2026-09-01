<?php

namespace App\Services;

use App\DTO\HeadOfficeDto;
use App\Repositories\HeadOfficeRepository;

class HeadOfficeService
{
    private $headOfficeRepository;

    public function __construct()
    {
        $this->headOfficeRepository = new HeadOfficeRepository();
    }

    public function findAll()
    {
        return $this->headOfficeRepository->findAll();
    }

    public function findById($id)
    {
        return $this->headOfficeRepository->findById($id);
    }

    public function create(HeadOfficeDto $dto)
    {
        return $this->headOfficeRepository->create((array) $dto);
    }

    public function update(HeadOfficeDto $dto, $id)
    {
        $data = (array) $dto;
        unset($data['created_by']);
        $data['is_approved'] = 0;
        $data['is_published'] = 0;
        $data['remarks'] = null;
        $data['publish_remark'] = null;
        return $this->headOfficeRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->headOfficeRepository->delete($id);
    }

    public function approve(HeadOfficeDto $dto, $id)
    {
        return $this->headOfficeRepository->update([
            'is_approved' => $dto->is_approved,
            'remarks' => $dto->remarks,
            'publish_remark' => $dto->publish_remark,
            'updated_by' => $dto->updated_by,
        ], $id);
    }

    public function publish(HeadOfficeDto $dto, $id)
    {
        return $this->headOfficeRepository->update([
            'is_published' => $dto->is_published,
            'is_approved' => $dto->is_approved,
            'remarks' => $dto->remarks,
            'publish_remark' => $dto->publish_remark,
            'updated_by' => $dto->updated_by,
        ], $id);
    }
    
    public function findForPublic($limit = null)
    {
        return $this->headOfficeRepository->findForPublic($limit);
    }
}
