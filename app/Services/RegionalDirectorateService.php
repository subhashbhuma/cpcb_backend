<?php

namespace App\Services;

use App\DTO\RegionalDirectorateDto;
use App\Repositories\RegionalDirectorateRepository;

class RegionalDirectorateService
{
    protected $repository;

    public function __construct(RegionalDirectorateRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(RegionalDirectorateDto $dto)
    {
        return $this->repository->create($dto->toArray());
    }

    public function update(RegionalDirectorateDto $dto, $id)
    {
        $data = $dto->toArray();
        // Prevent overwriting created_by on update
        unset($data['created_by']);
        $data['is_approved'] = 0;
        $data['is_published'] = 0;
        $data['remarks'] = null;
        $data['publish_remark'] = null;
        return $this->repository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    public function findById($id)
    {
        return $this->repository->findById($id);
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function approve(RegionalDirectorateDto $dto, $id)
    {
        $data = [
            'is_approved' => $dto->is_approved,
            'remarks' => $dto->remarks,
            'publish_remark' => $dto->publish_remark,
            'updated_by' => $dto->updated_by,
        ];
        return $this->repository->update($data, $id);
    }

    public function publish(RegionalDirectorateDto $dto, $id)
    {
        $data = [
            'is_published' => $dto->is_published,
            'is_approved' => $dto->is_approved,
            'remarks' => $dto->remarks,
            'publish_remark' => $dto->publish_remark,
            'updated_by' => $dto->updated_by,
        ];
        return $this->repository->update($data, $id);
    }

    public function findForPublic()
    {
        return $this->repository->findForPublic();
    }

    
}
