<?php

namespace App\Services;

use App\Repositories\DivisionRepository;
use App\DTO\DivisionDto;

class DivisionService
{
    private $divisionRepository;

    public function __construct()
    {
        $this->divisionRepository = new DivisionRepository();
    }

    public function findForPublic($limit = 10)
    {
        return $this->divisionRepository->findForPublic($limit);
    }

    public function findPublished($is_new=0)
    {
        return $this->divisionRepository->findPublished($is_new);
    }

    public function findAll()
    {
        return $this->divisionRepository->findAll();
    }

    public function findById($id)
    {
        return $this->divisionRepository->findById($id);
    }

    public function create(DivisionDto $dto)
    {
        return $this->divisionRepository->create($dto->toArray());
    }

    public function update(DivisionDto $dto, $id)
    {
        $data = $dto->toArray();
        unset($data['created_by']);

        // Reset on update
        $data['is_approved'] = 0;
        $data['is_published'] = 0;
        $data['remarks'] = null;
        $data['publish_remark'] = null;

        return $this->divisionRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->divisionRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $data = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->divisionRepository->update($data, $id);
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
        return $this->divisionRepository->update($data, $id);
    }
}
