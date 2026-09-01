<?php

namespace App\Services;

use App\DTO\AnnouncementDto;
use App\Repositories\AnnouncementRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class AnnouncementService
{
    use FileUploadTrait;
    private $announcementRepository;

    public function __construct()
    {
        $this->announcementRepository = new AnnouncementRepository();
    }

    public function findForPublic($limit = 10)
    {
        return $this->announcementRepository->findForPublic($limit);
    }

    public function findForPublicHomepage($limit = 10)
    {
        return $this->announcementRepository->findForPublicHomepage($limit);
    }

    public function findAll()
    {
        return $this->announcementRepository->findAll();
    }

    public function findById($id)
    {
        return $this->announcementRepository->findById($id);
    }

    public function create(AnnouncementDto $announcementDto)
    {
        // Upload file if type is "file"
        if ($announcementDto->file_or_link === 'file' && $announcementDto->file_name) {
            $uploaded = $this->uploadFile($announcementDto->file_name, Config::get('file_paths')['ANNOUNCEMENT_FILE_EN_PATH']);
            $announcementDto->file_name = $uploaded['file_name'];
        }

        // Upload file_name_hi if exists
        if ($announcementDto->file_or_link === 'file' && $announcementDto->file_name_hi) {
            $uploadedHi = $this->uploadFile($announcementDto->file_name_hi, Config::get('file_paths')['ANNOUNCEMENT_FILE_HI_PATH']);
            $announcementDto->file_name_hi = $uploadedHi['file_name'];
        }

        $result = $this->announcementRepository->create([
            'title' => $announcementDto->title,
            'title_hi' => $announcementDto->title_hi,
            'file_or_link' => $announcementDto->file_or_link,
            'file_name' => $announcementDto->file_or_link === 'file' ? $announcementDto->file_name : null,
            'file_name_hi' => $announcementDto->file_name_hi,
            'page_link' => $announcementDto->file_or_link === 'link' ? $announcementDto->page_link : null,
            'status' => $announcementDto->status,
            'is_approved' => $announcementDto->is_approved,
            'is_published' => $announcementDto->is_published,
            'remarks' => $announcementDto->remarks,
            'publish_remark' => $announcementDto->publish_remark,
            'created_by' => $announcementDto->created_by,
            'updated_by' => $announcementDto->updated_by,
            'published_date' => $announcementDto->published_date,
        ]);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function update(AnnouncementDto $announcementDto, $id)
    {
        // Upload file if type is "file"
        if ($announcementDto->file_or_link === 'file' && $announcementDto->file_name) {
            $uploaded = $this->uploadFile($announcementDto->file_name, Config::get('file_paths')['ANNOUNCEMENT_FILE_EN_PATH']);
            $announcementDto->file_name = $uploaded['file_name'];
        }

        // Upload file_name_hi if exists
        if ($announcementDto->file_name_hi) {
            $uploadedHi = $this->uploadFile($announcementDto->file_name_hi, Config::get('file_paths')['ANNOUNCEMENT_FILE_HI_PATH']);
            $announcementDto->file_name_hi = $uploadedHi['file_name'];
        }

        $updateData = [
            'title' => $announcementDto->title,
            'title_hi' => $announcementDto->title_hi,
            'file_or_link' => $announcementDto->file_or_link,
            'page_link' => $announcementDto->file_or_link === 'link' ? $announcementDto->page_link : null,
            'status' => $announcementDto->status,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'updated_by' => $announcementDto->updated_by,
            'published_date' => $announcementDto->published_date,
        ];

        if ($announcementDto->file_or_link === 'file') {
            if ($announcementDto->file_name) {
                $updateData['file_name'] = $announcementDto->file_name;
            }

            if ($announcementDto->file_name_hi) {
                $updateData['file_name_hi'] = $announcementDto->file_name_hi;
            }
        }


        $result = $this->announcementRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }


    public function delete($id)
    {
        return $this->announcementRepository->delete($id);
    }

    public function approve(AnnouncementDto $announcementDto, $id)
    {
        $updateData = [
            'is_approved' => $announcementDto->is_approved,
            'remarks' => $announcementDto->remarks,
            'publish_remark' => $announcementDto->publish_remark,
            'updated_by' => $announcementDto->updated_by,
        ];

        $result = $this->announcementRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function publish(AnnouncementDto $announcementDto, $id)
    {
        $updateData = [
            'is_approved' => $announcementDto->is_approved,
            'is_published' => $announcementDto->is_published,
            'remarks' => $announcementDto->remarks,
            'publish_remark' => $announcementDto->publish_remark,
            'updated_by' => $announcementDto->updated_by,
            'published_date' => $announcementDto->published_date,
        ];

        $result = $this->announcementRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }
}
