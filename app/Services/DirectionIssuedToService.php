<?php

namespace App\Services;

use App\DTO\DirectionIssuedToDto;
use App\Repositories\DirectionIssuedToRepository;

class DirectionIssuedToService
{
    protected $directionIssuedToRepository;

    public function __construct()
    {
        $this->directionIssuedToRepository = new DirectionIssuedToRepository();
    }

    public function findForPublic($limit = null)
    {
        return $this->directionIssuedToRepository->findForPublic($limit);
    }

    public function findByActType($actTypeId)
    {
        return $this->directionIssuedToRepository->findByActType($actTypeId);
    }

    public function findAll()
    {
        return $this->directionIssuedToRepository->findAll();
    }

    public function findById($id)
    {
        return $this->directionIssuedToRepository->findById($id);
    }

    public function create(DirectionIssuedToDto $directionIssuedToDto)
    {
        return $this->directionIssuedToRepository->create($directionIssuedToDto->toArray());
    }

    public function update(DirectionIssuedToDto $directionIssuedToDto, $id)
    {
        $data = $directionIssuedToDto->toArray();
        unset($data['created_by']);
        unset($data['created_at']);

        // Reset on update
        $data['is_approved'] = 0;
        $data['is_published'] = 0;
        $data['remarks'] = null;
        $data['publish_remark'] = null;

        return $this->directionIssuedToRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->directionIssuedToRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $data = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->directionIssuedToRepository->update($data, $id);
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

        return $this->directionIssuedToRepository->update($data, $id);
    }
}
