<?php

namespace App\Services;

use App\DTO\QueryFormSubjectDto;
use App\Repositories\QueryFormSubjectRepository;

class QueryFormSubjectService
{
    private $queryFormSubjectRepository;

    public function __construct()
    {
        $this->queryFormSubjectRepository = new QueryFormSubjectRepository();
    }

    public function findForPublic($limit = null)
    {
        return $this->queryFormSubjectRepository->findForPublic($limit);
    }

    public function findAll()
    {
        return $this->queryFormSubjectRepository->findAll();
    }

    public function findById($id)
    {
        return $this->queryFormSubjectRepository->findById($id);
    }

    public function fetchByIdForPublic($id)
    {
        return $this->queryFormSubjectRepository->fetchByIdForPublic($id);
    }

    public function create(QueryFormSubjectDto $dto)
    {
        return $this->queryFormSubjectRepository->create($dto->toArray());
    }

    public function update(QueryFormSubjectDto $dto, $id)
    {
        $data = $dto->toArray();
        unset($data['created_by']);

        // Reset on update
        $data['is_approved'] = 0;
        $data['is_published'] = 0;
        $data['remarks'] = null;
        $data['publish_remark'] = null;

        return $this->queryFormSubjectRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->queryFormSubjectRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $data = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->queryFormSubjectRepository->update($data, $id);
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
        return $this->queryFormSubjectRepository->update($data, $id);
    }
}
