<?php

namespace App\Services;

use App\DTO\AgraAirQualityDto;
use App\Repositories\AgraAirQualityRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class AgraAirQualityService
{
    use FileUploadTrait;

    protected $agraAirQualityRepository;

    public function __construct(AgraAirQualityRepository $agraAirQualityRepository)
    {
        $this->agraAirQualityRepository = $agraAirQualityRepository;
    }

    public function getAllAgraAirQualities()
    {
        return $this->agraAirQualityRepository->findAll();
    }

    public function getAllAgraAirQualitiesDataTable()
    {
        return $this->agraAirQualityRepository->getAllAgraAirQualitiesDataTable();
    }

    public function getAgraAirQualityById($id)
    {
        return $this->agraAirQualityRepository->findById($id);
    }

    public function createAgraAirQuality(AgraAirQualityDto $dto)
    {
        $data = $dto->toArray();

        if ($dto->file_name && !is_string($dto->file_name)) {
            $uploaded = $this->uploadFile($dto->file_name, Config::get('file_paths')['AGRA_AIR_QUALITY_FILE_EN_PATH']);
            $data['file_name'] = $uploaded['file_name'];
        }

        if ($dto->file_name_hi && !is_string($dto->file_name_hi)) {
            $uploadedHi = $this->uploadFile($dto->file_name_hi, Config::get('file_paths')['AGRA_AIR_QUALITY_FILE_HI_PATH']);
            $data['file_name_hi'] = $uploadedHi['file_name'];
        }

        return $this->agraAirQualityRepository->create($data);
    }

    public function updateAgraAirQuality($id, AgraAirQualityDto $dto)
    {
        $data = $dto->toArray();
        unset($data['created_by']);

        if ($dto->file_name && !is_string($dto->file_name)) {
            $uploaded = $this->uploadFile($dto->file_name, Config::get('file_paths')['AGRA_AIR_QUALITY_FILE_EN_PATH']);
            $data['file_name'] = $uploaded['file_name'];
        } else {
            unset($data['file_name']);
        }

        if ($dto->file_name_hi && !is_string($dto->file_name_hi)) {
            $uploadedHi = $this->uploadFile($dto->file_name_hi, Config::get('file_paths')['AGRA_AIR_QUALITY_FILE_HI_PATH']);
            $data['file_name_hi'] = $uploadedHi['file_name'];
        } else {
            unset($data['file_name_hi']);
        }

        // Reset logic
        $data['is_approved'] = 0;
        $data['is_published'] = 0;
        $data['remarks'] = null;
        $data['publish_remark'] = null;

        return $this->agraAirQualityRepository->update($data, $id);
    }

    public function deleteAgraAirQuality($id)
    {
        return $this->agraAirQualityRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $data = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => Auth::id(),
        ];
        return $this->agraAirQualityRepository->update($data, $id);
    }

    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null)
    {
        $data = [
            'is_published' => $isPublished,
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => Auth::id(),
        ];
        return $this->agraAirQualityRepository->update($data, $id);
    }
}
