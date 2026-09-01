<?php

namespace App\Services;

use App\DTO\FaqDto;
use App\DTO\QuickLinkDto;
use App\Repositories\FaqRepository;
use App\Repositories\QuickLinkRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class FaqService
{
    use FileUploadTrait;
    private $faqRepository;

    public function __construct()
    {
        $this->faqRepository = new FaqRepository();
    }

    public function findForPublic($limit = 7)
    {
        return $this->faqRepository->findForPublic($limit);
    }
    public function findAllForPublic($search)
    {
        return $this->faqRepository->findAllForPublic($search);
    }

    public function findAll()
    {
        return $this->faqRepository->findAll();
    }

    public function findById($id)
    {
        return $this->faqRepository->findById($id);
    }

    public function create(FaqDto $faqDto)
    {
        $result = $this->faqRepository->create([
            'question' => $faqDto->question,
            'question_hi' => $faqDto->question_hi,
            'answer' => $faqDto->answer,
            'answer_hi' => $faqDto->answer_hi,
            'is_approved' => $faqDto->is_approved,
            'is_published' => $faqDto->is_published,
            'remarks' => $faqDto->remarks,
            'publish_remark' => $faqDto->publish_remark,
            'created_by' => $faqDto->created_by,
            'updated_by' => $faqDto->updated_by,
        ]);

        if (!$result) {
            return false;
        }

        return $result;
    }


    public function update(FaqDto $faqDto, $id)
    {
        $updateData = [
            'question' => $faqDto->question,
            'question_hi' => $faqDto->question_hi,
            'answer' => $faqDto->answer,
            'answer_hi' => $faqDto->answer_hi,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'created_by' => $faqDto->created_by,
            'updated_by' => $faqDto->updated_by,
        ];

        $result = $this->faqRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function delete($id)
    {
        return $this->faqRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $updatedData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->faqRepository->update($updatedData, $id);
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
        return $this->faqRepository->update($updatedData, $id);
    }
}
