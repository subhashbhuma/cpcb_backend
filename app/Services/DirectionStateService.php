<?php

namespace App\Services;

use App\DTO\DirectionStateDto;
use App\Repositories\DirectionStateRepository;

class DirectionStateService
{
    protected $directionStateRepository;

    public function __construct()
    {
        $this->directionStateRepository = new DirectionStateRepository();
    }

    public function findForPublic($limit = null)
    {
        return $this->directionStateRepository->findForPublic($limit);
    }

    public function findAll()
    {
        return $this->directionStateRepository->findAll();
    }

    public function findById($id)
    {
        return $this->directionStateRepository->findById($id);
    }

    public function create(DirectionStateDto $directionStateDto)
    {
        return $this->directionStateRepository->create($directionStateDto->toArray());
    }

    public function update(DirectionStateDto $directionStateDto, $id)
    {
        $data = $directionStateDto->toArray();
        unset($data['created_by']);
        unset($data['created_at']);

        // Reset on update
        $data['is_approved'] = 0;
        $data['is_published'] = 0;
        $data['remarks'] = null;
        $data['publish_remark'] = null;

        return $this->directionStateRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->directionStateRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $data = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->directionStateRepository->update($data, $id);
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

        return $this->directionStateRepository->update($data, $id);
    }
}
