<?php

namespace App\Services;

use App\DTO\QuickLinkDto;
use App\Repositories\QuickLinkRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class QuickLinkService
{
    use FileUploadTrait;
    private $quickLinkRepository;

    public function __construct()
    {
        $this->quickLinkRepository = new QuickLinkRepository();
    }

    public function findForPublic($limit = 10)
    {
        return $this->quickLinkRepository->findForPublic($limit);
    }
    public function findAllForPublic()
    {
        return $this->quickLinkRepository->findAllForPublic();
    }

    public function findAll()
    {
        return $this->quickLinkRepository->findAll();
    }

    public function findById($id)
    {
        return $this->quickLinkRepository->findById($id);
    }

    public function create(QuickLinkDto $quickLinkDto)
    {
        $result = $this->quickLinkRepository->create([
            'title' => $quickLinkDto->title,
            'title_hi' => $quickLinkDto->title_hi,
            'description' => $quickLinkDto->description,
            'description_hi' => $quickLinkDto->description_hi,
            'link' => $quickLinkDto->link,
            'created_by' => $quickLinkDto->created_by,
            'updated_by' => $quickLinkDto->updated_by,
        ]);

        if (!$result) {
            return false;
        }

        return $result;
    }


    public function update(QuickLinkDto $quickLinkDto, $id)
    {
        $updateData = [
            'title' => $quickLinkDto->title,
            'title_hi' => $quickLinkDto->title_hi,
            'description' => $quickLinkDto->description,
            'description_hi' => $quickLinkDto->description_hi,
            'link' => $quickLinkDto->link,
            'created_by' => $quickLinkDto->created_by,
            'updated_by' => $quickLinkDto->updated_by,
        ];

        $result = $this->quickLinkRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function delete($id)
    {
        return $this->quickLinkRepository->delete($id);
    }

    public function approve(QuickLinkDto $quickLinkDto, $id)
    {
        $updateData = [
            'is_approved' => $quickLinkDto->is_approved,
            'remarks' => $quickLinkDto->remarks,
            'updated_by' => $quickLinkDto->updated_by,
        ];

        $result = $this->quickLinkRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function publish(QuickLinkDto $quickLinkDto, $id)
    {
        $updateData = [
            'is_approved' => $quickLinkDto->is_approved,
            'is_published' => $quickLinkDto->is_published,
            'remarks' => $quickLinkDto->remarks,
            'updated_by' => $quickLinkDto->updated_by,
        ];

        $result = $this->quickLinkRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }
}
