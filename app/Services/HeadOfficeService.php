<?php

namespace App\Services;

use App\DTO\HeadOfficeDto;
use App\Repositories\HeadOfficeRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class HeadOfficeService
{
    use FileUploadTrait;

    private $headOfficeRepository;
    private $officeItemService;

    public function __construct()
    {
        $this->headOfficeRepository = new HeadOfficeRepository();
        $this->officeItemService = new OfficeItemService();
    }

    public function findAll()
    {
        return $this->headOfficeRepository->findAll();
    }

    public function findById($id)
    {
        return $this->headOfficeRepository->findById($id);
    }

    public function create(HeadOfficeDto $dto, array $personnels = [], array $profileActivities = [])
    {
        return DB::transaction(function () use ($dto, $personnels, $profileActivities) {
            if ($dto->image) {
                $file = $this->uploadFile($dto->image, Config::get('file_paths')['HEAD_OFFICE_IMAGE_PATH']);
                $dto->image = $file['file_name'];
            }

            $headOffice = $this->headOfficeRepository->create((array) $dto);

            if (!empty($personnels)) {
                $this->officeItemService->syncPersonnels('head_offices', $headOffice->id, $personnels, $dto->created_by);
            }

            if (!empty($profileActivities)) {
                $this->officeItemService->syncProfileActivities('head_offices', $headOffice->id, $profileActivities, $dto->created_by);
            }

            return $headOffice;
        });
    }

    public function update(HeadOfficeDto $dto, $id, array $personnels = [], array $profileActivities = [])
    {
        return DB::transaction(function () use ($dto, $id, $personnels, $profileActivities) {
            $data = (array) $dto;
            unset($data['created_by']);
            $data['is_approved'] = 0;
            $data['is_published'] = 0;
            $data['remarks'] = null;
            $data['publish_remark'] = null;

            if ($dto->image) {
                $file = $this->uploadFile($dto->image, Config::get('file_paths')['HEAD_OFFICE_IMAGE_PATH']);
                $data['image'] = $file['file_name'];
            } else {
                unset($data['image']);
            }

            $headOffice = $this->headOfficeRepository->update($data, $id);

            $this->officeItemService->syncPersonnels('head_offices', $id, $personnels, $dto->updated_by);
            $this->officeItemService->syncProfileActivities('head_offices', $id, $profileActivities, $dto->updated_by);

            return $headOffice;
        });
    }


    public function delete($id)
    {
        return $this->headOfficeRepository->delete($id);
    }

    public function approve(HeadOfficeDto $dto, $id)
    {
        return $this->headOfficeRepository->update([
            'is_approved' => $dto->is_approved,
            'remarks' => $dto->remarks,
            'publish_remark' => $dto->publish_remark,
            'updated_by' => $dto->updated_by,
        ], $id);
    }

    public function publish(HeadOfficeDto $dto, $id)
    {
        return $this->headOfficeRepository->update([
            'is_published' => $dto->is_published,
            'is_approved' => $dto->is_approved,
            'remarks' => $dto->remarks,
            'publish_remark' => $dto->publish_remark,
            'updated_by' => $dto->updated_by,
        ], $id);
    }
    
    public function findForPublic($limit = null)
    {
        return $this->headOfficeRepository->findForPublic($limit);
    }
}
