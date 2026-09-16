<?php

namespace App\Services;

use App\DTO\RegionalDirectorateDto;
use App\Repositories\RegionalDirectorateRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class RegionalDirectorateService
{
    use FileUploadTrait;

    protected $repository;
    protected $officeItemService;

    public function __construct(RegionalDirectorateRepository $repository)
    {
        $this->repository = $repository;
        $this->officeItemService = new OfficeItemService();
    }

    public function create(RegionalDirectorateDto $dto, array $personnels = [], array $profileActivities = [], array $states = [])
    {
        return DB::transaction(function () use ($dto, $personnels, $profileActivities, $states) {
            if ($dto->image) {
                $file = $this->uploadFile($dto->image, Config::get('file_paths')['REGIONAL_DIRECTORATE_IMAGE_PATH']);
                $dto->image = $file['file_name'];
            }

            $regionalDirectorate = $this->repository->create($dto->toArray());

            if (!empty($personnels)) {
                $this->officeItemService->syncPersonnels('regional_directorates', $regionalDirectorate->id, $personnels, $dto->created_by);
            }

            if (!empty($profileActivities)) {
                $this->officeItemService->syncProfileActivities('regional_directorates', $regionalDirectorate->id, $profileActivities, $dto->created_by);
            }

            if (!empty($states)) {
                $this->officeItemService->syncRegionalDirectorateStates($regionalDirectorate->id, $states, $dto->created_by);
            }

            return $regionalDirectorate;
        });
    }

    public function update(RegionalDirectorateDto $dto, $id, array $personnels = [], array $profileActivities = [], array $states = [])
    {
        return DB::transaction(function () use ($dto, $id, $personnels, $profileActivities, $states) {
            if ($dto->image) {
                $file = $this->uploadFile($dto->image, Config::get('file_paths')['REGIONAL_DIRECTORATE_IMAGE_PATH']);
                $dto->image = $file['file_name'];
            }

            $data = $dto->toArray();
            // Prevent overwriting created_by on update
            unset($data['created_by']);
            $data['is_approved'] = 0;
            $data['is_published'] = 0;
            $data['remarks'] = null;
            $data['publish_remark'] = null;
            
            $this->repository->update($data, $id);

            $this->officeItemService->syncPersonnels('regional_directorates', $id, $personnels, $dto->updated_by);
            $this->officeItemService->syncProfileActivities('regional_directorates', $id, $profileActivities, $dto->updated_by);
            $this->officeItemService->syncRegionalDirectorateStates($id, $states, $dto->updated_by);

            return $this->findById($id);
        });
    }


    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    public function findById($id)
    {
        return $this->repository->findById($id);
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function approve(RegionalDirectorateDto $dto, $id)
    {
        $data = [
            'is_approved' => $dto->is_approved,
            'remarks' => $dto->remarks,
            'publish_remark' => $dto->publish_remark,
            'updated_by' => $dto->updated_by,
        ];
        return $this->repository->update($data, $id);
    }

    public function publish(RegionalDirectorateDto $dto, $id)
    {
        $data = [
            'is_published' => $dto->is_published,
            'is_approved' => $dto->is_approved,
            'remarks' => $dto->remarks,
            'publish_remark' => $dto->publish_remark,
            'updated_by' => $dto->updated_by,
        ];
        return $this->repository->update($data, $id);
    }

    public function findForPublic()
    {
        return $this->repository->findForPublic();
    }

    
}
