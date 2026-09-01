<?php

namespace App\Services;

use App\DTO\DirectionSubjectDto;
use App\Repositories\DirectionSubjectRepository;

class DirectionSubjectService
{
    protected $directionSubjectRepository;

    public function __construct()
    {
        $this->directionSubjectRepository = new DirectionSubjectRepository();
    }

    public function findForPublic($limit = null)
    {
        return $this->directionSubjectRepository->findForPublic($limit);
    }

    public function findByActType($actTypeId)
    {
        return $this->directionSubjectRepository->findByActType($actTypeId);
    }

    public function findAll()
    {
        return $this->directionSubjectRepository->findAll();
    }

    public function findById($id)
    {
        return $this->directionSubjectRepository->findById($id);
    }

    public function create(DirectionSubjectDto $directionSubjectDto)
    {
        return $this->directionSubjectRepository->create($directionSubjectDto->toArray());
    }

    public function update(DirectionSubjectDto $directionSubjectDto, $id)
    {
        $data = $directionSubjectDto->toArray();
        unset($data['created_by']);
        unset($data['created_at']);

        // Reset on update
        $data['is_approved'] = 0;
        $data['is_published'] = 0;
        $data['remarks'] = null;
        $data['publish_remark'] = null;

        return $this->directionSubjectRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->directionSubjectRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $data = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->directionSubjectRepository->update($data, $id);
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

        return $this->directionSubjectRepository->update($data, $id);
    }
}
