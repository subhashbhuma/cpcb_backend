<?php

namespace App\Services;

use App\DTO\ZonalOfficeDto;
use App\Repositories\ZonalOfficeRepository;

class ZonalOfficeServices
{
    private $zonalOfficeRepository;

    public function __construct()
    {
        $this->zonalOfficeRepository = new ZonalOfficeRepository();
    }

    public function findForPublic($limit = null)
    {
        return $this->zonalOfficeRepository->findForPublic($limit);
    }
    public function findAll()
    {
        return $this->zonalOfficeRepository->findAll();
    }
    public function findById($id)
    {
        return $this->zonalOfficeRepository->findById($id);
    }
    public function fetchByIdForPublic($id)
    {
        return $this->zonalOfficeRepository->fetchByIdForPublic($id);
    }

    public function create(ZonalOfficeDto $zonalOfficeDto)
    {
        return $this->zonalOfficeRepository->create([
            'title' => $zonalOfficeDto->title,
            'title_hi' => $zonalOfficeDto->title_hi,
            'is_approved' => $zonalOfficeDto->is_approved,
            'is_published' => $zonalOfficeDto->is_published,
            'remarks' => $zonalOfficeDto->remarks,
            'publish_remark' => $zonalOfficeDto->publish_remark,
            'created_by' => $zonalOfficeDto->created_by,
            'created_at' => $zonalOfficeDto->created_at,
            'updated_by' => $zonalOfficeDto->updated_by,
            'updated_at' => $zonalOfficeDto->updated_at,
        ]);
    }
    public function update(ZonalOfficeDto $zonalOfficeDto, $id)
    {
        $data = [
            'title' => $zonalOfficeDto->title,
            'title_hi' => $zonalOfficeDto->title_hi,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'created_by' => $zonalOfficeDto->created_by,
            'created_at' => $zonalOfficeDto->created_at,
            'updated_by' => $zonalOfficeDto->updated_by,
            'updated_at' => $zonalOfficeDto->updated_at,
        ];
        return $this->zonalOfficeRepository->update($data, $id);
    }
    public function delete($id)
    {
        return $this->zonalOfficeRepository->delete($id);
    }
    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $updatedData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->zonalOfficeRepository->update($updatedData, $id);
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
        return $this->zonalOfficeRepository->update($updatedData, $id);
    }
}
