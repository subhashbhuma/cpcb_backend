<?php

namespace App\Services;

use App\DTO\LatestCpcbDto;
use App\Repositories\LatestCpcbRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class LatestCpcbService
{
    use FileUploadTrait;
    private $latestCpcbRepository;

    public function __construct()
    {
        $this->latestCpcbRepository = new LatestCpcbRepository();
    }

    public function findForPublicWithPagination($perPage = 10)
    {
        return $this->latestCpcbRepository->findForPublicWithPagination($perPage);
    }

    public function findForPublic($limit = null)
    {
        return $this->latestCpcbRepository->findForPublic($limit);
    }

    public function findAll()
    {
        return $this->latestCpcbRepository->findAll();
    }

    public function findById($id)
    {
        return $this->latestCpcbRepository->findById($id);
    }

    public function create(LatestCpcbDto $latestCpcbDto)
    {
        if ($latestCpcbDto->file_name) {
            $uploaded = $this->uploadFile($latestCpcbDto->file_name, Config::get('file_paths')['LATEST_CPCB_FILE_EN_PATH']);
            $latestCpcbDto->file_name = $uploaded['file_name'];
        }

        if ($latestCpcbDto->file_name_hi) {
            $uploadedHi = $this->uploadFile($latestCpcbDto->file_name_hi, Config::get('file_paths')['LATEST_CPCB_FILE_HI_PATH']);
            $latestCpcbDto->file_name_hi = $uploadedHi['file_name'];
        }

        return $this->latestCpcbRepository->create([
            'title' => $latestCpcbDto->title,
            'title_hi' => $latestCpcbDto->title_hi,
            'publish_date' => $latestCpcbDto->publish_date,
            'division_id' => $latestCpcbDto->division_id,
            'file_name' => $latestCpcbDto->file_name,
            'file_name_hi' => $latestCpcbDto->file_name_hi,
            'is_approved' => $latestCpcbDto->is_approved,
            'is_published' => $latestCpcbDto->is_published,
            'remarks' => $latestCpcbDto->remarks,
            'publish_remark' => $latestCpcbDto->publish_remark,
            'created_by' => $latestCpcbDto->created_by,
            'updated_by' => $latestCpcbDto->updated_by,
        ]);
    }

    public function update(LatestCpcbDto $latestCpcbDto, $id)
    {
        if ($latestCpcbDto->file_name) {
            $uploaded = $this->uploadFile($latestCpcbDto->file_name, Config::get('file_paths')['LATEST_CPCB_FILE_EN_PATH']);
            $latestCpcbDto->file_name = $uploaded['file_name'];
        }

        if ($latestCpcbDto->file_name_hi) {
            $uploadedHi = $this->uploadFile($latestCpcbDto->file_name_hi, Config::get('file_paths')['LATEST_CPCB_FILE_HI_PATH']);
            $latestCpcbDto->file_name_hi = $uploadedHi['file_name'];
        }

        $data = [
            'title' => $latestCpcbDto->title,
            'title_hi' => $latestCpcbDto->title_hi,
            'publish_date' => $latestCpcbDto->publish_date,
            'division_id' => $latestCpcbDto->division_id,
            'is_approved' => $latestCpcbDto->is_approved,
            'is_published' => $latestCpcbDto->is_published,
            'remarks' => $latestCpcbDto->remarks,
            'publish_remark' => $latestCpcbDto->publish_remark,
            'created_by' => $latestCpcbDto->created_by,
            'updated_by' => $latestCpcbDto->updated_by,
        ];

        if ($latestCpcbDto->file_name) {
            $data['file_name'] = $latestCpcbDto->file_name;
        }

        if ($latestCpcbDto->file_name_hi) {
            $data['file_name_hi'] = $latestCpcbDto->file_name_hi;
        }

        return $this->latestCpcbRepository->update($data, $id);
    }


    public function delete($id)
    {
        return $this->latestCpcbRepository->delete($id);
    }

    public function approve(LatestCpcbDto $latestCpcbDto, $id)
    {
        $updateData = [
            'is_approved' => $latestCpcbDto->is_approved,
            'remarks' => $latestCpcbDto->remarks,
            'publish_remark' => $latestCpcbDto->publish_remark,
            'updated_by' => $latestCpcbDto->updated_by,
        ];

        $result = $this->latestCpcbRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function publish(LatestCpcbDto $latestCpcbDto, $id)
    {
        $updateData = [
            'is_approved' => $latestCpcbDto->is_approved,
            'is_published' => $latestCpcbDto->is_published,
            'remarks' => $latestCpcbDto->remarks,
            'publish_remark' => $latestCpcbDto->publish_remark,
            'updated_by' => $latestCpcbDto->updated_by,
        ];

        $result = $this->latestCpcbRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }
}
