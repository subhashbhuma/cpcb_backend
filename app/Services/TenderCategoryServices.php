<?php

namespace App\Services;

use App\DTO\TenderCategoryDto;
use App\Repositories\TenderCategoryRepository;

class TenderCategoryServices
{
    private $tenderCategoryRepository;

    public function __construct()
    {
        $this->tenderCategoryRepository = new TenderCategoryRepository();
    }

    public function findForPublic($limit = null)
    {
        return $this->tenderCategoryRepository->findForPublic($limit);
    }
    public function findAll()
    {
        return $this->tenderCategoryRepository->findAll();
    }
    public function findById($id)
    {
        return $this->tenderCategoryRepository->findById($id);
    }
    public function fetchByIdForPublic($id)
    {
        return $this->tenderCategoryRepository->fetchByIdForPublic($id);
    }

    public function create(TenderCategoryDto $tenderCategoryDto)
    {
        return $this->tenderCategoryRepository->create([
            'title' => $tenderCategoryDto->title,
            'title_hi' => $tenderCategoryDto->title_hi,
            'is_approved' => $tenderCategoryDto->is_approved,
            'is_published' => $tenderCategoryDto->is_published,
            'remarks' => $tenderCategoryDto->remarks,
            'publish_remark' => $tenderCategoryDto->publish_remark,
            'created_by' => $tenderCategoryDto->created_by,
            'created_at' => $tenderCategoryDto->created_at,
            'updated_by' => $tenderCategoryDto->updated_by,
            'updated_at' => $tenderCategoryDto->updated_at,
        ]);
    }
    public function update(TenderCategoryDto $tenderCategoryDto, $id)
    {
        $data = [
            'title' => $tenderCategoryDto->title,
            'title_hi' => $tenderCategoryDto->title_hi,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'created_by' => $tenderCategoryDto->created_by,
            'created_at' => $tenderCategoryDto->created_at,
            'updated_by' => $tenderCategoryDto->updated_by,
            'updated_at' => $tenderCategoryDto->updated_at,
        ];
        return $this->tenderCategoryRepository->update($data, $id);
    }
    public function delete($id)
    {
        return $this->tenderCategoryRepository->delete($id);
    }
    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $updatedData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->tenderCategoryRepository->update($updatedData, $id);
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
        return $this->tenderCategoryRepository->update($updatedData, $id);
    }
}
