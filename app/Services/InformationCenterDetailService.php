<?php

namespace App\Services;

use App\DTO\InformationCenterDetailDto;
use App\Models\InformationCenterDetail;
use App\Repositories\InformationCenterDetailRepository;
use App\Traits\FileUploadTrait;

class InformationCenterDetailService
{
    use FileUploadTrait;
    protected $repository;
    public function __construct()
    {
        $this->repository = new InformationCenterDetailRepository();
    }


    public function getAll()
    {
        return $this->repository->findAll();
    }

    public function getById($id): InformationCenterDetail
    {
        return $this->repository->findById($id);
    }

    public function getByInformationCenterId($centerId)
    {
        return $this->repository->findByInformationCenterId($centerId);
    }

    public function getByInformationCenterIdForPublic($centerId)
    {
        return $this->repository->findByInformationCenterIdForPublic($centerId);
    }

    public function create(InformationCenterDetailDto $dto, $request = null): InformationCenterDetail
    {
        $data = $dto->toArray();

        if ($request) {
            if ($request->hasFile('file_name')) {
                $uploadResult = $this->uploadFile(
                    $request->file('file_name'),
                    config('file_paths.INFORMATION_CENTER_FILE_EN_PATH')
                );
                if ($uploadResult) {
                    $data['file_name'] = $uploadResult['file_name'];
                }
            }

            if ($request->hasFile('file_name_hi')) {
                $uploadResult = $this->uploadFile(
                    $request->file('file_name_hi'),
                    config('file_paths.INFORMATION_CENTER_FILE_HI_PATH')
                );
                if ($uploadResult) {
                    $data['file_name_hi'] = $uploadResult['file_name'];
                }
            }
        }

        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        return $this->repository->create($data);
    }

    public function update($id, InformationCenterDetailDto $dto, $request = null): InformationCenterDetail
    {
        $detail = $this->getById($id);
        $data = $dto->toArray();

        if ($request) {
            if ($request->hasFile('file_name')) {

                $uploadResult = $this->uploadFile(
                    $request->file('file_name'),
                    config('file_paths.INFORMATION_CENTER_FILE_EN_PATH')
                );
                if ($uploadResult) {
                    $data['file_name'] = $uploadResult['file_name'];
                }
            }

            if ($request->hasFile('file_name_hi')) {
                $uploadResult = $this->uploadFile(
                    $request->file('file_name_hi'),
                    config('file_paths.INFORMATION_CENTER_FILE_HI_PATH')
                );
                if ($uploadResult) {
                    $data['file_name_hi'] = $uploadResult['file_name'];
                }
            }
        }

        $data['is_approved'] = 0;
        $data['is_published'] = 0;
        $data['remarks'] = null;
        $data['publish_remark'] = null;
        $data['updated_by'] = auth()->id();

        return $this->repository->update($id, $data);
    }

    public function delete($id): bool
    {
        $detail = $this->getById($id);
        return $this->repository->delete($id);
    }

    public function approve($id, $isApproved, $remarks = null, $publishRemark = null): InformationCenterDetail
    {
        return $this->repository->update($id, [
            'is_approved' => $isApproved,
            'remarks' => $remarks,
            'publish_remark' => $publishRemark,
            'updated_by' => auth()->id(),
        ]);
    }

    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null): InformationCenterDetail
    {
        return $this->repository->update($id, [
            'is_approved' => $isApproved,
            'remarks' => $remarks,
            'is_published' => $isPublished,
            'publish_remark' => $publishRemark,
            'updated_by' => auth()->id(),
        ]);
    }
}
