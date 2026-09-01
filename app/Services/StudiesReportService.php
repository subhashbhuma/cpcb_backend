<?php

namespace App\Services;

use App\DTO\StudiesReportDto;
use App\Repositories\StudiesReportRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class StudiesReportService
{
    use FileUploadTrait;
    private $studiesReportRepository;

    public function __construct()
    {
        $this->studiesReportRepository = new StudiesReportRepository();
    }

    public function findAll()
    {
        return $this->studiesReportRepository->findAll();
    }

    public function findById($id)
    {
        return $this->studiesReportRepository->findById($id);
    }

    public function create(StudiesReportDto $dto)
    {
        if ($dto->file_name) {
            $uploaded = $this->uploadFile($dto->file_name, Config::get('file_paths')['STUDIES_REPORT_FILE_EN_PATH']);
            $dto->file_name = $uploaded['file_name'];
        }

        if ($dto->file_name_hi) {
            $uploadedHi = $this->uploadFile($dto->file_name_hi, Config::get('file_paths')['STUDIES_REPORT_FILE_HI_PATH']);
            $dto->file_name_hi = $uploadedHi['file_name'];
        }

        return $this->studiesReportRepository->create([
            'title' => $dto->title,
            'title_hi' => $dto->title_hi,
            'division_id' => $dto->division_id,
            'report_year' => $dto->report_year,
            'file_name' => $dto->file_name,
            'file_name_hi' => $dto->file_name_hi,
            'is_approved' => $dto->is_approved,
            'is_published' => $dto->is_published,
            'remarks' => $dto->remarks,
            'publish_remark' => $dto->publish_remark,
            'created_by' => $dto->created_by,
            'updated_by' => $dto->updated_by,
        ]);
    }

    public function update(StudiesReportDto $dto, $id)
    {
        $existing = $this->studiesReportRepository->findById($id);

        if ($dto->file_name && is_object($dto->file_name)) {
            $uploaded = $this->uploadFile($dto->file_name, Config::get('file_paths')['STUDIES_REPORT_FILE_EN_PATH']);
            $dto->file_name = $uploaded['file_name'];
        } else {
            $dto->file_name = $existing->file_name;
        }

        if ($dto->file_name_hi && is_object($dto->file_name_hi)) {
            $uploadedHi = $this->uploadFile($dto->file_name_hi, Config::get('file_paths')['STUDIES_REPORT_FILE_HI_PATH']);
            $dto->file_name_hi = $uploadedHi['file_name'];
        } else {
            $dto->file_name_hi = $existing->file_name_hi;
        }

        return $this->studiesReportRepository->update([
            'title' => $dto->title,
            'title_hi' => $dto->title_hi,
            'division_id' => $dto->division_id,
            'report_year' => $dto->report_year,
            'file_name' => $dto->file_name,
            'file_name_hi' => $dto->file_name_hi,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'updated_by' => $dto->updated_by,
        ], $id);
    }

    public function delete($id)
    {
        return $this->studiesReportRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        return $this->studiesReportRepository->update([
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ], $id);
    }

    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null)
    {
        return $this->studiesReportRepository->update([
            'is_published' => $isPublished,
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ], $id);
    }

    public function findForPublic($limit = null)
    {
        return $this->studiesReportRepository->findForPublic($limit);
    }

}
