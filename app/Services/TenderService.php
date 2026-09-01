<?php

namespace App\Services;

use App\DTO\TenderDto;
use App\Repositories\TenderRepository;
use App\Traits\FileUploadTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class TenderService
{
    use FileUploadTrait;
    private $tenderRepository;

    public function __construct()
    {
        $this->tenderRepository = new TenderRepository();
    }

    public function findForPublicWithPagination($perPage = 10)
    {
        return $this->tenderRepository->findForPublicWithPagination($perPage);
    }

    public function findForPublic($limit = null)
    {
        return $this->tenderRepository->findForPublic($limit);
    }

    public function findAll()
    {
        return $this->tenderRepository->findAll();
    }

    public function findById($id)
    {
        return $this->tenderRepository->findById($id);
    }

    public function create(TenderDto $tenderDto)
    {
        if ($tenderDto->file_name) {
            $uploaded = $this->uploadFile($tenderDto->file_name, Config::get('file_paths')['TENDER_FILE_EN_PATH']);
            $tenderDto->file_name = $uploaded['file_name'];
        }

        if ($tenderDto->file_name_hi) {
            $uploadedHi = $this->uploadFile($tenderDto->file_name_hi, Config::get('file_paths')['TENDER_FILE_HI_PATH']);
            $tenderDto->file_name_hi = $uploadedHi['file_name'];
        }

        return $this->tenderRepository->create([
            'division_id' => $tenderDto->division_id,
            'title' => $tenderDto->title,
            'title_hi' => $tenderDto->title_hi,
            'issuing_authority' => $tenderDto->issuing_authority,
            'issuing_authority_hi' => $tenderDto->issuing_authority_hi,
            'publish_date' => $this->normalizeDateTime($tenderDto->publish_date),
            'start_date' => $this->normalizeDateTime($tenderDto->start_date),
            'end_date' => $this->normalizeDateTime($tenderDto->end_date),
            'file_name' => $tenderDto->file_name,
            'file_name_hi' => $tenderDto->file_name_hi,
            'is_approved' => $tenderDto->is_approved,
            'is_published' => $tenderDto->is_published,
            'remarks' => $tenderDto->remarks,
            'publish_remark' => $tenderDto->publish_remark,
            'created_by' => $tenderDto->created_by,
            'updated_by' => $tenderDto->updated_by,
        ]);
    }

    public function update(TenderDto $tenderDto, $id)
    {
        if ($tenderDto->file_name) {
            $uploaded = $this->uploadFile($tenderDto->file_name, Config::get('file_paths')['TENDER_FILE_EN_PATH']);
            $tenderDto->file_name = $uploaded['file_name'];
        }

        if ($tenderDto->file_name_hi) {
            $uploadedHi = $this->uploadFile($tenderDto->file_name_hi, Config::get('file_paths')['TENDER_FILE_HI_PATH']);
            $tenderDto->file_name_hi = $uploadedHi['file_name'];
        }

        $data = [
            'division_id' => $tenderDto->division_id,
            'title' => $tenderDto->title,
            'title_hi' => $tenderDto->title_hi,
            'issuing_authority' => $tenderDto->issuing_authority,
            'issuing_authority_hi' => $tenderDto->issuing_authority_hi,
            'publish_date' => $this->normalizeDateTime($tenderDto->publish_date),
            'start_date' => $this->normalizeDateTime($tenderDto->start_date),
            'end_date' => $this->normalizeDateTime($tenderDto->end_date),
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'created_by' => $tenderDto->created_by,
            'updated_by' => $tenderDto->updated_by,
        ];

        if ($tenderDto->file_name) {
            $data['file_name'] = $tenderDto->file_name;
        }

        if ($tenderDto->file_name_hi) {
            $data['file_name_hi'] = $tenderDto->file_name_hi;
        }

        return $this->tenderRepository->update($data, $id);
    }


    public function delete($id)
    {
        return $this->tenderRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $updatedData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->tenderRepository->update($updatedData, $id);
    }

    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null)
    {
        $updatedData = [
            'is_published' => $isPublished,
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->tenderRepository->update($updatedData, $id);
    }

    private function normalizeDateTime($value)
    {
        return $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : null;
    }
}
