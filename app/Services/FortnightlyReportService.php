<?php

namespace App\Services;

use App\DTO\FortnightlyReportDto;
use App\Repositories\FortnightlyReportRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class FortnightlyReportService
{
    use FileUploadTrait;
    private $fortnightlyReportRepository;

    public function __construct()
    {
        $this->fortnightlyReportRepository = new FortnightlyReportRepository();
    }

    public function findForPublicWithPagination($perPage = 10)
    {
        return $this->fortnightlyReportRepository->findForPublicWithPagination($perPage);
    }

    public function findForPublic($limit = null)
    {
        return $this->fortnightlyReportRepository->findForPublic($limit);
    }

    public function findAll()
    {
        return $this->fortnightlyReportRepository->findAll();
    }

    public function findById($id)
    {
        return $this->fortnightlyReportRepository->findById($id);
    }

    public function create(FortnightlyReportDto $dto)
    {
        $data = $dto->toArray();

        if ($dto->file_name) {
            $uploaded = $this->uploadFile($dto->file_name, Config::get('file_paths')['FORTNIGHTLY_REPORT_FILE_EN_PATH']);
            $data['file_name'] = $uploaded['file_name'];
        }

        if ($dto->file_name_hi) {
            $uploadedHi = $this->uploadFile($dto->file_name_hi, Config::get('file_paths')['FORTNIGHTLY_REPORT_FILE_HI_PATH']);
            $data['file_name_hi'] = $uploadedHi['file_name'];
        }

        return $this->fortnightlyReportRepository->create($data);
    }

    public function update(FortnightlyReportDto $dto, $id)
    {
        $data = $dto->toArray();
        unset($data['created_by']);

        if ($dto->file_name) {
            $uploaded = $this->uploadFile($dto->file_name, Config::get('file_paths')['FORTNIGHTLY_REPORT_FILE_EN_PATH']);
            $data['file_name'] = $uploaded['file_name'];
        } else {
            unset($data['file_name']);
        }

        if ($dto->file_name_hi) {
            $uploadedHi = $this->uploadFile($dto->file_name_hi, Config::get('file_paths')['FORTNIGHTLY_REPORT_FILE_HI_PATH']);
            $data['file_name_hi'] = $uploadedHi['file_name'];
        } else {
            unset($data['file_name_hi']);
        }

        // Reset on update
        $data['is_approved'] = 0;
        $data['is_published'] = 0;
        $data['remarks'] = null;
        $data['publish_remark'] = null;

        return $this->fortnightlyReportRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->fortnightlyReportRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $data = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->fortnightlyReportRepository->update($data, $id);
    }

    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null)
    {
        $data = [
            'is_published' => $isPublished,
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->fortnightlyReportRepository->update($data, $id);
    }
}
