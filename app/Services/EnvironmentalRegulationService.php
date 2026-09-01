<?php

namespace App\Services;

use App\Repositories\EnvironmentalRegulationRepository;
use App\DTO\EnvironmentalRegulationDto;

class EnvironmentalRegulationService
{
    private $environmentalRegulationRepository;

    public function __construct()
    {
        $this->environmentalRegulationRepository = new EnvironmentalRegulationRepository();
    }

    public function findForPublic($limit = 10)
    {
        return $this->environmentalRegulationRepository->findForPublic($limit);
    }
    public function findPublished()
    {
        return $this->environmentalRegulationRepository->findPublished();
    }



    public function findAll()
    {
        return $this->environmentalRegulationRepository->findAll();
    }

    public function findById($id)
    {
        return $this->environmentalRegulationRepository->findById($id);
    }

    public function create(EnvironmentalRegulationDto $environmentalRegulationDto)
    {

        $photoGallery = $this->environmentalRegulationRepository->create([
            'title' => $environmentalRegulationDto->title,
            'title_hi' => $environmentalRegulationDto->title_hi,
            'created_by' => $environmentalRegulationDto->created_by,
        ]);

        if (!$photoGallery) {
            return false;
        }

        return $photoGallery;
    }

    public function update(EnvironmentalRegulationDto $environmentalRegulationDto, $id)
    {
        $updateData = [
            'title' => $environmentalRegulationDto->title,
            'title_hi' => $environmentalRegulationDto->title_hi,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'updated_by' => $environmentalRegulationDto->updated_by,
        ];

        $photoGallery = $this->environmentalRegulationRepository->update($updateData, $id);

        if (!$photoGallery) {
            return false;
        }

        return $this->findById($id);
    }

    public function delete($id)
    {
        return $this->environmentalRegulationRepository->delete($id);
    }

    public function approve(EnvironmentalRegulationDto $environmentalRegulationDto, $id)
    {
        $updateData = [
            'is_approved' => $environmentalRegulationDto->is_approved,
            'remarks' => $environmentalRegulationDto->remarks,
            'publish_remark' => $environmentalRegulationDto->publish_remark,
            'updated_by' => $environmentalRegulationDto->updated_by,
        ];

        $result = $this->environmentalRegulationRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function publish(EnvironmentalRegulationDto $environmentalRegulationDto, $id)
    {
        $updateData = [
            'is_approved' => $environmentalRegulationDto->is_approved,
            'is_published' => $environmentalRegulationDto->is_published,
            'remarks' => $environmentalRegulationDto->remarks,
            'publish_remark' => $environmentalRegulationDto->publish_remark,
            'updated_by' => $environmentalRegulationDto->updated_by,
        ];

        $result = $this->environmentalRegulationRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }
}
