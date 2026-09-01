<?php

namespace App\Services;

use App\Repositories\SubjectAreaRepository;
use App\DTO\SubjectAreaDto;

class SubjectAreaService
{
    private $subjectAreaRepository;

    public function __construct()
    {
        $this->subjectAreaRepository = new SubjectAreaRepository();
    }


    public function findForPublic($limit = 10)
    {
        return $this->subjectAreaRepository->findForPublic($limit);
    }

    public function findPublished()
    {
        return $this->subjectAreaRepository->findPublished();
    }

    public function findAll()
    {
        return $this->subjectAreaRepository->findAll();
    }

    public function findById($id)
    {
        return $this->subjectAreaRepository->findById($id);
    }

    public function create(SubjectAreaDto $SubjectAreaDto)
    {

        $photoGallery = $this->subjectAreaRepository->create([
            'title' => $SubjectAreaDto->title,
            'title_hi' => $SubjectAreaDto->title_hi,
            'created_by' => $SubjectAreaDto->created_by,
        ]);

        if (!$photoGallery) {
            return false;
        }

        return $photoGallery;
    }

    public function update(SubjectAreaDto $SubjectAreaDto, $id)
    {
        $updateData = [
            'title' => $SubjectAreaDto->title,
            'title_hi' => $SubjectAreaDto->title_hi,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'updated_by' => $SubjectAreaDto->updated_by,
        ];

        $result = $this->subjectAreaRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $this->findById($id);
    }

    public function delete($id)
    {
        return $this->subjectAreaRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $updateData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->subjectAreaRepository->update($updateData, $id);
    }

    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null)
    {
        $updateData = [
            'is_published' => $isPublished,
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->subjectAreaRepository->update($updateData, $id);
    }
}
