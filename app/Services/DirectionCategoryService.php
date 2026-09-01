<?php

namespace App\Services;

use App\DTO\DirectionCategoryDto;
use App\Repositories\DirectionCategoryRepository;

class DirectionCategoryService
{
    protected $directionCategoryRepository;

    public function __construct()
    {
        $this->directionCategoryRepository = new DirectionCategoryRepository();
    }

    public function findForPublic($limit = null)
    {
        return $this->directionCategoryRepository->findForPublic($limit);
    }

    public function findByActType($actTypeId)
    {
        return $this->directionCategoryRepository->findByActType($actTypeId);
    }

    public function findAll()
    {
        return $this->directionCategoryRepository->findAll();
    }

    public function findById($id)
    {
        return $this->directionCategoryRepository->findById($id);
    }

    public function create(DirectionCategoryDto $directionCategoryDto)
    {
        return $this->directionCategoryRepository->create($directionCategoryDto->toArray());
    }

    public function update(DirectionCategoryDto $directionCategoryDto, $id)
    {
        $data = $directionCategoryDto->toArray();
        unset($data['created_by']);
        unset($data['created_at']);

        // Reset on update
        $data['is_approved'] = 0;
        $data['is_published'] = 0;
        $data['remarks'] = null;
        $data['publish_remark'] = null;

        return $this->directionCategoryRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->directionCategoryRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $data = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->directionCategoryRepository->update($data, $id);
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

        return $this->directionCategoryRepository->update($data, $id);
    }
}
