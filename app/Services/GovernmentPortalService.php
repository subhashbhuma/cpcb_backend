<?php

namespace App\Services;

use App\DTO\GovernmentPortalDto;
use App\Repositories\GovernmentPortalRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class GovernmentPortalService
{
    use FileUploadTrait;
    private $governmentPortalRepository;

    public function __construct()
    {
        $this->governmentPortalRepository = new GovernmentPortalRepository();
    }

    public function findForPublic()
    {
        return $this->governmentPortalRepository->findForPublic();
    }

    public function findAll()
    {
        return $this->governmentPortalRepository->findAll();
    }

    public function findById($id)
    {
        return $this->governmentPortalRepository->findById($id);
    }

    public function create(GovernmentPortalDto $governmentPortalDto)
    {
        // Upload header logo
        if ($governmentPortalDto->file_name) {
            $headerFile = $this->uploadFile($governmentPortalDto->file_name, Config::get('file_paths')['GOVERNMENT_PORTAL_IMAGE_PATH']);
            $governmentPortalDto->file_name = $headerFile['file_name'];
        }

        $result = $this->governmentPortalRepository->create([
            'file_name' => $governmentPortalDto->file_name,
            'title' => $governmentPortalDto->title,
            'title_hi' => $governmentPortalDto->title_hi,
            'link' => $governmentPortalDto->link,
            'created_by' => $governmentPortalDto->created_by,
            'updated_by' => $governmentPortalDto->updated_by,
        ]);

        if (!$result) {
            return false;
        }

        return $result;
    }


    public function update(GovernmentPortalDto $governmentPortalDto, $id)
    {
        // Upload header logo
        if ($governmentPortalDto->file_name) {
            $headerFile = $this->uploadFile($governmentPortalDto->file_name, Config::get('file_paths')['GOVERNMENT_PORTAL_IMAGE_PATH']);
            $governmentPortalDto->file_name = $headerFile['file_name'];
        }

        $updateData = [
            'title' => $governmentPortalDto->title,
            'title_hi' => $governmentPortalDto->title_hi,
            'link' => $governmentPortalDto->link,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'updated_by' => $governmentPortalDto->updated_by,
        ];

        if ($governmentPortalDto->file_name) {
            $updateData['file_name'] = $governmentPortalDto->file_name;
        }

        return $this->governmentPortalRepository->update($updateData, $id);
    }

    public function delete($id)
    {
        return $this->governmentPortalRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $updateData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->governmentPortalRepository->update($updateData, $id);
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

        return $this->governmentPortalRepository->update($updateData, $id);
    }
}
