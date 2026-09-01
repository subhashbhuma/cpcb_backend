<?php

namespace App\Services;

use App\DTO\NgtCourtCaseDto;
use App\Repositories\NgtCourtCaseRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class NgtCourtCaseService
{
    use FileUploadTrait;
    private $ngtCourtCaseRepository;

    public function __construct()
    {
        $this->ngtCourtCaseRepository = new NgtCourtCaseRepository();
    }

    public function findForPublicWithPagination($perPage = 10)
    {
        return $this->ngtCourtCaseRepository->findForPublicWithPagination($perPage);
    }

    public function findForPublic($limit = null)
    {
        return $this->ngtCourtCaseRepository->findForPublic($limit);
    }

    public function findAll()
    {
        return $this->ngtCourtCaseRepository->findAll();
    }

    public function findById($id)
    {
        return $this->ngtCourtCaseRepository->findById($id);
    }

    public function create(NgtCourtCaseDto $dto)
    {
        $data = $dto->toArray();

        if ($dto->file_name) {
            $uploaded = $this->uploadFile($dto->file_name, Config::get('file_paths')['NGT_COURT_CASE_FILE_EN_PATH']);
            $data['file_name'] = $uploaded['file_name'];
        }

        if ($dto->file_name_hi) {
            $uploadedHi = $this->uploadFile($dto->file_name_hi, Config::get('file_paths')['NGT_COURT_CASE_FILE_HI_PATH']);
            $data['file_name_hi'] = $uploadedHi['file_name'];
        }

        return $this->ngtCourtCaseRepository->create($data);
    }

    public function update(NgtCourtCaseDto $dto, $id)
    {
        $data = $dto->toArray();
        unset($data['created_by']);

        if ($dto->file_name) {
            $uploaded = $this->uploadFile($dto->file_name, Config::get('file_paths')['NGT_COURT_CASE_FILE_EN_PATH']);
            $data['file_name'] = $uploaded['file_name'];
        } else {
            unset($data['file_name']);
        }

        if ($dto->file_name_hi) {
            $uploadedHi = $this->uploadFile($dto->file_name_hi, Config::get('file_paths')['NGT_COURT_CASE_FILE_HI_PATH']);
            $data['file_name_hi'] = $uploadedHi['file_name'];
        } else {
            unset($data['file_name_hi']);
        }

        // Reset on update
        $data['is_approved'] = 0;
        $data['is_published'] = 0;
        $data['remarks'] = null;
        $data['publish_remark'] = null;

        return $this->ngtCourtCaseRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->ngtCourtCaseRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $data = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->ngtCourtCaseRepository->update($data, $id);
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
        return $this->ngtCourtCaseRepository->update($data, $id);
    }
}
