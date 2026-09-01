<?php

namespace App\Services;

use App\DTO\QuickLinkDto;
use App\DTO\RegionalDirectoriesDto;
use App\Repositories\RegionalDirectoriesRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class RegionalDirectoriesService
{
    use FileUploadTrait;
    private $regionalDirectoriesRepository;

    public function __construct()
    {
        $this->regionalDirectoriesRepository = new RegionalDirectoriesRepository();
    }

    public function findForPublic($limit = 10)
    {
        return $this->regionalDirectoriesRepository->findForPublic($limit);
    }
    public function findAllForPublic()
    {
        return $this->regionalDirectoriesRepository->findAllForPublic();
    }

    public function findAll()
    {
        return $this->regionalDirectoriesRepository->findAll();
    }

    public function findById($id)
    {
        return $this->regionalDirectoriesRepository->findById($id);
    }

    public function findByZone($zone)
    {
        return $this->regionalDirectoriesRepository->findByZone($zone);
    }

    public function create(RegionalDirectoriesDto $regionalDirectoriesDto)
    {
        $result = $this->regionalDirectoriesRepository->create([
            'zone' => $regionalDirectoriesDto->zone,
            'state' => $regionalDirectoriesDto->state,
            'address' => $regionalDirectoriesDto->address,
            'phone_numbers' => $regionalDirectoriesDto->phone_numbers,
            'email_ids' => $regionalDirectoriesDto->email_ids,
            'jurdiction' => $regionalDirectoriesDto->jurdiction,
            'location_link' => $regionalDirectoriesDto->location_link,
            'is_approved' => $regionalDirectoriesDto->is_approved,
            'is_published' => $regionalDirectoriesDto->is_published,
            'remarks' => $regionalDirectoriesDto->remarks,
            'created_by' => $regionalDirectoriesDto->created_by,
            'updated_by' => $regionalDirectoriesDto->updated_by,
        ]);

        if (!$result) {
            return false;
        }

        return $result;
    }


    public function update(RegionalDirectoriesDto $regionalDirectoriesDto, $id)
    {
        $updateData = [
            'zone' => $regionalDirectoriesDto->zone,
            'state' => $regionalDirectoriesDto->state,
            'address' => $regionalDirectoriesDto->address,
            'phone_numbers' => $regionalDirectoriesDto->phone_numbers,
            'email_ids' => $regionalDirectoriesDto->email_ids,
            'jurdiction' => $regionalDirectoriesDto->jurdiction,
            'location_link' => $regionalDirectoriesDto->location_link,
            'is_approved' => $regionalDirectoriesDto->is_approved,
            'is_published' => $regionalDirectoriesDto->is_published,
            'remarks' => $regionalDirectoriesDto->remarks,
            'created_by' => $regionalDirectoriesDto->created_by,
            'updated_by' => $regionalDirectoriesDto->updated_by,
        ];

        $result = $this->regionalDirectoriesRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function delete($id)
    {
        return $this->regionalDirectoriesRepository->delete($id);
    }

    public function approve(RegionalDirectoriesDto $regionalDirectoriesDto, $id)
    {
        $updateData = [
            'is_approved' => $regionalDirectoriesDto->is_approved,
            'remarks' => $regionalDirectoriesDto->remarks,
            'updated_by' => $regionalDirectoriesDto->updated_by,
        ];

        $result = $this->regionalDirectoriesRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function publish(RegionalDirectoriesDto $regionalDirectoriesDto, $id)
    {
        $updateData = [
            'is_approved' => $regionalDirectoriesDto->is_approved,
            'is_published' => $regionalDirectoriesDto->is_published,
            'remarks' => $regionalDirectoriesDto->remarks,
            'updated_by' => $regionalDirectoriesDto->updated_by,
        ];

        $result = $this->regionalDirectoriesRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }
}
