<?php

namespace App\Services;

use App\DTO\ComplaintFormSubjectDto;
use App\Repositories\ComplaintFormSubjectRepository;

class ComplaintFormSubjectService
{
    protected $complaintFormSubjectRepository;

    public function __construct()
    {
        $this->complaintFormSubjectRepository = new ComplaintFormSubjectRepository();
    }

    public function findForPublic($limit = null)
    {
        return $this->complaintFormSubjectRepository->findForPublic($limit);
    }

    public function findAll()
    {
        return $this->complaintFormSubjectRepository->findAll();
    }

    public function findById($id)
    {
        return $this->complaintFormSubjectRepository->findById($id);
    }

    public function create(ComplaintFormSubjectDto $dto)
    {
        return $this->complaintFormSubjectRepository->create($dto->toArray());
    }

    public function update(ComplaintFormSubjectDto $dto, $id)
    {
        $data = $dto->toArray();
        unset($data['created_by']);

        // Reset on update
        $data['is_approved'] = 0;
        $data['is_published'] = 0;
        $data['remarks'] = null;
        $data['publish_remark'] = null;

        return $this->complaintFormSubjectRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->complaintFormSubjectRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $data = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->complaintFormSubjectRepository->update($data, $id);
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
        return $this->complaintFormSubjectRepository->update($data, $id);
    }
}
