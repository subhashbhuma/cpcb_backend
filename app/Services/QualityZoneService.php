<?php

namespace App\Services;

use App\DTO\QualityZoneDto;
use App\Repositories\QualityZoneRepository;

class QualityZoneService
{
    private $qualityZoneRepository;

    public function __construct()
    {
        $this->qualityZoneRepository = new QualityZoneRepository();
    }

    public function findForPublicWithPagination($perPage = 10)
    {
        return $this->qualityZoneRepository->findForPublicWithPagination($perPage);
    }

    public function findForPublic($limit = null)
    {
        return $this->qualityZoneRepository->findForPublic($limit);
    }

    public function findAll()
    {
        return $this->qualityZoneRepository->findAll();
    }

    public function findById($id)
    {
        return $this->qualityZoneRepository->findById($id);
    }

    public function create(QualityZoneDto $dto)
    {
        return $this->qualityZoneRepository->create($dto->toArray());
    }

    public function update(QualityZoneDto $dto, $id)
    {
        $data = $dto->toArray();
        unset($data['created_by']);

        // Reset on update
        $data['is_approved'] = 0;
        $data['is_published'] = 0;
        $data['remarks'] = null;
        $data['publish_remark'] = null;

        return $this->qualityZoneRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->qualityZoneRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $data = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->qualityZoneRepository->update($data, $id);
    }

    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null)
    {
        $data = [
            'is_published' => $isPublished,
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->qualityZoneRepository->update($data, $id);
    }
}
