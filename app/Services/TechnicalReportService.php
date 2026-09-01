<?php

namespace App\Services;

use App\DTO\TechnicalReportDto;
use App\Repositories\TechnicalReportRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class TechnicalReportService
{
    use FileUploadTrait;
    private $technicalRepository;

    public function __construct()
    {
        $this->technicalRepository = new TechnicalReportRepository();
    }


    public function findForPublic($limit = null)
    {
        return $this->technicalRepository->findForPublic($limit);
    }

    public function findAll()
    {
        return $this->technicalRepository->findAll();
    }

    public function findById($id)
    {
        return $this->technicalRepository->findById($id);
    }

    public function create(TechnicalReportDto $technical_report_dto)
    {
        if ($technical_report_dto->file_name) {
            $uploaded = $this->uploadFile($technical_report_dto->file_name, Config::get('file_paths')['TECHNICAL_REPORT_FILE_EN_PATH']);
            $technical_report_dto->file_name = $uploaded['file_name'];
        }

        if ($technical_report_dto->file_name_hi) {
            $uploadedHi = $this->uploadFile($technical_report_dto->file_name_hi, Config::get('file_paths')['TECHNICAL_REPORT_FILE_HI_PATH']);
            $technical_report_dto->file_name_hi = $uploadedHi['file_name'];
        }

        return $this->technicalRepository->create([
            'subject_area_id' => $technical_report_dto->subject_area_id,
            'division_id' => $technical_report_dto->division_id,
            'title' => $technical_report_dto->title,
            'title_hi' => $technical_report_dto->title_hi,
            'release_date' => $technical_report_dto->release_date,
            'file_name' => $technical_report_dto->file_name,
            'file_name_hi' => $technical_report_dto->file_name_hi,
            'is_approved' => $technical_report_dto->is_approved,
            'is_published' => $technical_report_dto->is_published,
            'remarks' => $technical_report_dto->remarks,
            'publish_remark' => $technical_report_dto->publish_remark,
            'created_by' => $technical_report_dto->created_by,
            'created_at' => $technical_report_dto->created_at,
            'updated_by' => $technical_report_dto->updated_by,
            'updated_at' => $technical_report_dto->updated_at,
        ]);
    }

    public function update(TechnicalReportDto $technicalReportDto, $id)
    {
        if ($technicalReportDto->file_name) {
            $uploaded = $this->uploadFile($technicalReportDto->file_name, Config::get('file_paths')['TECHNICAL_REPORT_FILE_EN_PATH']);
            $technicalReportDto->file_name = $uploaded['file_name'];
        }

        if ($technicalReportDto->file_name_hi) {
            $uploadedHi = $this->uploadFile($technicalReportDto->file_name_hi, Config::get('file_paths')['TECHNICAL_REPORT_FILE_HI_PATH']);
            $technicalReportDto->file_name_hi = $uploadedHi['file_name'];
        }

        $data = [
            'subject_area_id' => $technicalReportDto->subject_area_id,
            'division_id' => $technicalReportDto->division_id,
            'title' => $technicalReportDto->title,
            'title_hi' => $technicalReportDto->title_hi,
            'release_date' => $technicalReportDto->release_date,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'created_by' => $technicalReportDto->created_by,
            'created_at' => $technicalReportDto->created_at,
            'updated_by' => $technicalReportDto->updated_by,
            'updated_at' => $technicalReportDto->updated_at,
        ];

        if ($technicalReportDto->file_name) {
            $data['file_name'] = $technicalReportDto->file_name;
        }

        if ($technicalReportDto->file_name_hi) {
            $data['file_name_hi'] = $technicalReportDto->file_name_hi;
        }

        return $this->technicalRepository->update($data, $id);
    }


    public function delete($id)
    {
        return $this->technicalRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $updateData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->technicalRepository->update($updateData, $id);
    }

    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null)
    {
        $updateData = [
            'is_published' => $isPublished,
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->technicalRepository->update($updateData, $id);
    }
}
