<?php

namespace App\Services;

use App\Repositories\DirectionTypeRepository;
use App\DTO\DirectionTypeDto;

class DirectionTypeService
{
    private $directionTypeRepository;

    public function __construct()
    {
        $this->directionTypeRepository = new DirectionTypeRepository();
    }


    public function findForPublic($limit = 10)
    {
        return $this->directionTypeRepository->findForPublic($limit);
    }

    public function findByActType($actTypeId)
    {
        return $this->directionTypeRepository->findByActType($actTypeId);
    }

    public function findPublished()
    {
        return $this->directionTypeRepository->findPublished();
    }

    public function findAll()
    {
        return $this->directionTypeRepository->findAll();
    }

    public function findById($id)
    {
        return $this->directionTypeRepository->findById($id);
    }

    public function create(DirectionTypeDto $directionTypeDto)
    {
        return $this->directionTypeRepository->create($directionTypeDto->toArray());
    }

    public function update(DirectionTypeDto $directionTypeDto, $id)
    {
        $data = $directionTypeDto->toArray();
        unset($data['created_by']);

        // Reset on update
        $data['is_approved'] = 0;
        $data['is_published'] = 0;
        $data['remarks'] = null;
        $data['publish_remark'] = null;

        return $this->directionTypeRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->directionTypeRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $data = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->directionTypeRepository->update($data, $id);
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

        return $this->directionTypeRepository->update($data, $id);
    }
}
