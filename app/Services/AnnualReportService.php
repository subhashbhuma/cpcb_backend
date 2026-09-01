<?php

namespace App\Services;

use App\DTO\AnnualReportDto;
use App\DTO\JobDto;
use App\Models\AnnualReport;
use App\Repositories\AnnualReportRepository;
use App\Repositories\JobRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class AnnualReportService
{
    use FileUploadTrait;
    private $annualReportRepository;

    public function __construct()
    {
        $this->annualReportRepository = new AnnualReportRepository();
    }


    public function findForPublic($limit = null)
    {
        return $this->annualReportRepository->findForPublic($limit);
    }

    public function findAll()
    {
        return $this->annualReportRepository->findAll();
    }

    public function findById($id)
    {
        return $this->annualReportRepository->findById($id);
    }

    public function create(AnnualReportDto $annual_report_dto)
    {
        if ($annual_report_dto->file_name) {
            $uploaded = $this->uploadFile($annual_report_dto->file_name, Config::get('file_paths')['ANNUAL_REPORT_FILE_EN_PATH']);
            $annual_report_dto->file_name = $uploaded['file_name'];
        }

        if ($annual_report_dto->file_name_hi) {
            $uploadedHi = $this->uploadFile($annual_report_dto->file_name_hi, Config::get('file_paths')['ANNUAL_REPORT_FILE_HI_PATH']);
            $annual_report_dto->file_name_hi = $uploadedHi['file_name'];
        }



        return $this->annualReportRepository->create([
            'title' => $annual_report_dto->title,
            'title_hi' => $annual_report_dto->title_hi,
            'release_date' => $annual_report_dto->release_date,
            'file_name' => $annual_report_dto->file_name,
            'file_name_hi' => $annual_report_dto->file_name_hi,
            'is_approved' => $annual_report_dto->is_approved,
            'is_published' => $annual_report_dto->is_published,
            'remarks' => $annual_report_dto->remarks,
            'publish_remark' => $annual_report_dto->publish_remark,
            'created_by' => $annual_report_dto->created_by,
            'created_at' => $annual_report_dto->created_at,
            'updated_by' => $annual_report_dto->updated_by,
            'updated_at' => $annual_report_dto->updated_at,
        ]);
    }

    public function update(AnnualReportDto $annualReportDto, $id)
    {
        if ($annualReportDto->file_name) {
            $uploaded = $this->uploadFile($annualReportDto->file_name, Config::get('file_paths')['ANNUAL_REPORT_FILE_EN_PATH']);
            $annualReportDto->file_name = $uploaded['file_name'];
        }

        if ($annualReportDto->file_name_hi) {
            $uploadedHi = $this->uploadFile($annualReportDto->file_name_hi, Config::get('file_paths')['ANNUAL_REPORT_FILE_HI_PATH']);
            $annualReportDto->file_name_hi = $uploadedHi['file_name'];
        }


        $data = [
            'title' => $annualReportDto->title,
            'title_hi' => $annualReportDto->title_hi,
            'release_date' => $annualReportDto->release_date,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'created_by' => $annualReportDto->created_by,
            'created_at' => $annualReportDto->created_at,
            'updated_by' => $annualReportDto->updated_by,
            'updated_at' => $annualReportDto->updated_at,
        ];

        if ($annualReportDto->file_name) {
            $data['file_name'] = $annualReportDto->file_name;
        }

        if ($annualReportDto->file_name_hi) {
            $data['file_name_hi'] = $annualReportDto->file_name_hi;
        }



        return $this->annualReportRepository->update($data, $id);
    }


    public function delete($id)
    {
        return $this->annualReportRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $updateData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->annualReportRepository->update($updateData, $id);
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

        return $this->annualReportRepository->update($updateData, $id);
    }
}
