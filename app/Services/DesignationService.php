<?php

namespace App\Services;

use App\Repositories\DesignationRepository;
use App\DTO\DesignationDto;

class DesignationService
{
    private $designationRepository;

    public function __construct()
    {
        $this->designationRepository = new DesignationRepository();
    }

    public function findForPublic($limit = 10)
    {
        return $this->designationRepository->findForPublic($limit);
    }

    public function findPublished()
    {
        return $this->designationRepository->findPublished();
    }

    public function findAll()
    {
        return $this->designationRepository->findAll();
    }

    public function findById($id)
    {
        return $this->designationRepository->findById($id);
    }

    public function create(DesignationDto $dto)
    {
        return $this->designationRepository->create($dto->toArray());
    }

    public function update(DesignationDto $dto, $id)
    {
        $data = $dto->toArray();
        unset($data['created_by']);

        // Reset on update
        $data['is_approved'] = 0;
        $data['is_published'] = 0;
        $data['remarks'] = null;
        $data['publish_remark'] = null;

        return $this->designationRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->designationRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $data = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->designationRepository->update($data, $id);
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
        return $this->designationRepository->update($data, $id);
    }
}
