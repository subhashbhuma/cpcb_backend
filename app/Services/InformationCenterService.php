<?php

namespace App\Services;

use App\DTO\InformationCenterDto;
use App\Models\InformationCenter;
use App\Repositories\InformationCenterRepository;

class InformationCenterService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new InformationCenterRepository();
    }

    public function getAll($type = null)
    {
        return $this->repository->findAll($type);
    }

    public function getById($id): InformationCenter
    {
        return $this->repository->findById($id);
    }

    public function getPublished()
    {
        return $this->repository->findPublished();
    }

    public function getForPublic()
    {
        return $this->repository->findForPublic();
    }

    public function create(InformationCenterDto $dto): InformationCenter
    {
        return $this->repository->create($dto->toArray());
    }

    public function update($id, InformationCenterDto $dto): InformationCenter
    {
        $data = $dto->toArray();
        $data['is_approved'] = 0;
        $data['is_published'] = 0;
        $data['remarks'] = null;
        $data['publish_remark'] = null;
        return $this->repository->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->repository->delete($id);
    }

    public function approve($id, $isApproved, $remarks = null, $publishRemark = null): InformationCenter
    {
        return $this->repository->update($id, [
            'is_approved' => $isApproved,
            'remarks' => $remarks,
            'publish_remark' => $publishRemark,
            'updated_by' => auth()->id(),
        ]);
    }

    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null): InformationCenter
    {
        return $this->repository->update($id, [
            'is_approved' => $isApproved,
            'remarks' => $remarks,
            'is_published' => $isPublished,
            'publish_remark' => $publishRemark,
            'updated_by' => auth()->id(),
        ]);
    }
}
