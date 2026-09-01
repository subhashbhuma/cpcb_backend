<?php

namespace App\Services;

use App\DTO\PublicationCategoryDto;
use App\Repositories\PublicationCategoryRepository;

class PublicationCategoryServices
{
    private $publicationCategoryRepository;

    public function __construct()
    {
        $this->publicationCategoryRepository = new PublicationCategoryRepository();
    }

    public function findForPublic($limit = null)
    {
        return $this->publicationCategoryRepository->findForPublic($limit);
    }
    public function findAll()
    {
        return $this->publicationCategoryRepository->findAll();
    }
    public function findById($id)
    {
        return $this->publicationCategoryRepository->findById($id);
    }
    public function fetchByIdForPublic($id)
    {
        return $this->publicationCategoryRepository->fetchByIdForPublic($id);
    }

    public function create(PublicationCategoryDto $publicationCategoryDto)
    {
        return $this->publicationCategoryRepository->create([
            'title' => $publicationCategoryDto->title,
            'title_hi' => $publicationCategoryDto->title_hi,
            'code' => $publicationCategoryDto->code,
            'code_hi' => $publicationCategoryDto->code_hi,
            'is_approved' => $publicationCategoryDto->is_approved,
            'is_published' => $publicationCategoryDto->is_published,
            'remarks' => $publicationCategoryDto->remarks,
            'publish_remark' => $publicationCategoryDto->publish_remark,
            'created_by' => $publicationCategoryDto->created_by,
            'created_at' => $publicationCategoryDto->created_at,
            'updated_by' => $publicationCategoryDto->updated_by,
            'updated_at' => $publicationCategoryDto->updated_at,
        ]);
    }
    public function update(PublicationCategoryDto $publicationCategoryDto, $id)
    {
        $data = [
            'title' => $publicationCategoryDto->title,
            'title_hi' => $publicationCategoryDto->title_hi,
            'code' => $publicationCategoryDto->code,
            'code_hi' => $publicationCategoryDto->code_hi,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'created_by' => $publicationCategoryDto->created_by,
            'created_at' => $publicationCategoryDto->created_at,
            'updated_by' => $publicationCategoryDto->updated_by,
            'updated_at' => $publicationCategoryDto->updated_at,
        ];
        return $this->publicationCategoryRepository->update($data, $id);
    }
    public function delete($id)
    {
        return $this->publicationCategoryRepository->delete($id);
    }
    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $updatedData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->publicationCategoryRepository->update($updatedData, $id);
    }
    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null)
    {
        $updatedData = [
            'is_published' => $isPublished,
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->publicationCategoryRepository->update($updatedData, $id);
    }
}
