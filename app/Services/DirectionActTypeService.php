<?php

namespace App\Services;

use App\DTO\DirectionActTypeDto;
use App\Repositories\DirectionActTypeRepository;

class DirectionActTypeService
{
    protected $directionActTypeRepository;

    public function __construct()
    {
        $this->directionActTypeRepository = new DirectionActTypeRepository();
    }

    public function findForPublic($limit = null)
    {
        return $this->directionActTypeRepository->findForPublic($limit);
    }

    public function findAll()
    {
        return $this->directionActTypeRepository->findAll();
    }

    public function findById($id)
    {
        return $this->directionActTypeRepository->findById($id);
    }

    public function create(DirectionActTypeDto $directionActTypeDto)
    {
        return $this->directionActTypeRepository->create($directionActTypeDto->toArray());
    }

    public function update(DirectionActTypeDto $directionActTypeDto, $id)
    {
        $data = $directionActTypeDto->toArray();
        unset($data['created_by'], $data['created_at']);

        // Reset on update
        $data['is_approved'] = 0;
        $data['is_published'] = 0;
        $data['remarks'] = null;
        $data['publish_remark'] = null;

        return $this->directionActTypeRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->directionActTypeRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $data = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
            'updated_at' => now(),
        ];

        return $this->directionActTypeRepository->update($data, $id);
    }

    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null)
    {
        $data = [
            'is_approved' => $isApproved,
            'is_published' => $isPublished,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
            'updated_at' => now(),
        ];

        return $this->directionActTypeRepository->update($data, $id);
    }
}
