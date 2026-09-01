<?php

namespace App\Services;

use App\DTO\PortalDto;
use App\Repositories\PortalRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class PortalService
{
    use FileUploadTrait;
    private $portalRepository;

    public function __construct()
    {
        $this->portalRepository = new PortalRepository();
    }

    public function findForPublic($limit = null)
    {
        return $this->portalRepository->findForPublic($limit);
    }

    public function findAll()
    {
        return $this->portalRepository->findAll();
    }

    public function findById($id)
    {
        return $this->portalRepository->findById($id);
    }

    public function create(PortalDto $portalDto)
    {
        // Upload header logo
        if ($portalDto->file_name) {
            $headerFile = $this->uploadFile($portalDto->file_name, Config::get('file_paths')['CPCB_PORTAL_IMAGE_PATH']);
            $portalDto->file_name = $headerFile['file_name'];
        }

        $result = $this->portalRepository->create([
            'file_name' => $portalDto->file_name,
            'title' => $portalDto->title,
            'title_hi' => $portalDto->title_hi,
            'link' => $portalDto->link,
            'created_by' => $portalDto->created_by,
            'updated_by' => $portalDto->updated_by,
        ]);

        if (!$result) {
            return false;
        }

        return $result;
    }


    public function update(PortalDto $portalDto, $id)
    {
        // Upload header logo
        if ($portalDto->file_name) {
            $headerFile = $this->uploadFile($portalDto->file_name, Config::get('file_paths')['CPCB_PORTAL_IMAGE_PATH']);
            $portalDto->file_name = $headerFile['file_name'];
        }

        $updateData = [
            'title' => $portalDto->title,
            'title_hi' => $portalDto->title_hi,
            'link' => $portalDto->link,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'updated_by' => $portalDto->updated_by,
        ];

        if ($portalDto->file_name) {
            $updateData['file_name'] = $portalDto->file_name;
        }

        return $this->portalRepository->update($updateData, $id);
    }

    public function delete($id)
    {
        return $this->portalRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $updateData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->portalRepository->update($updateData, $id);
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

        return $this->portalRepository->update($updateData, $id);
    }
}
