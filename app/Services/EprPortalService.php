<?php

namespace App\Services;

use App\DTO\EprPortalDto;
use App\Repositories\EprPortalRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class EprPortalService
{
    use FileUploadTrait;
    private $eprPortalRepository;

    public function __construct()
    {
        $this->eprPortalRepository = new EprPortalRepository();
    }

    public function findForPublic($limit = null)
    {
        return $this->eprPortalRepository->findForPublic($limit);
    }

    public function findAll()
    {
        return $this->eprPortalRepository->findAll();
    }

    public function findById($id)
    {
        return $this->eprPortalRepository->findById($id);
    }

    public function create(EprPortalDto $eprPortalDto)
    {
        $result = $this->eprPortalRepository->create([
            'title' => $eprPortalDto->title,
            'title_hi' => $eprPortalDto->title_hi,
            'link' => $eprPortalDto->link,
            'is_live' => $eprPortalDto->is_live,
            'created_by' => $eprPortalDto->created_by,
            'updated_by' => $eprPortalDto->updated_by,
        ]);

        if (!$result) {
            return false;
        }

        return $result;
    }


    public function update(EprPortalDto $eprPortalDto, $id)
    {
        $updateData = [
            'title' => $eprPortalDto->title,
            'title_hi' => $eprPortalDto->title_hi,
            'link' => $eprPortalDto->link,
            'is_live' => $eprPortalDto->is_live,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'updated_by' => $eprPortalDto->updated_by,
        ];

        return $this->eprPortalRepository->update($updateData, $id);
    }

    public function delete($id)
    {
        return $this->eprPortalRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $updateData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->eprPortalRepository->update($updateData, $id);
    }

    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null)
    {
        $updateData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'is_published' => $isPublished,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->eprPortalRepository->update($updateData, $id);
    }
}
