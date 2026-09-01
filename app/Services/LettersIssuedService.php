<?php

namespace App\Services;

use App\DTO\LettersIssuedDto;
use App\Repositories\LettersIssuedRepository;
use Illuminate\Support\Facades\Config;
use App\Traits\FileUploadTrait;

class LettersIssuedService
{
    use FileUploadTrait;
    protected $repository;

    public function __construct()
    {
        $this->repository = new LettersIssuedRepository();
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function findById($id)
    {
        return $this->repository->findById($id);
    }

    public function fetchForDatatable($type = null)
    {
        return $this->repository->fetchForDatatable($type);
    }

    public function fetchAllForPublicDataTable($type = null)
    {
        return $this->repository->fetchAllForPublicDataTable($type);
    }

    public function create(LettersIssuedDto $dto)
    {
        $data = $dto->toArray();

        // Handle File Uploads
        if ($dto->file_name) {
            $uploaded = $this->uploadFile($dto->file_name, Config::get('file_paths')['LETTERS_ISSUED_FILE_EN_PATH']);
            $data['file_name'] = $uploaded['file_name'];
        }
        if ($dto->file_name_hi) {
            $uploadedHi = $this->uploadFile($dto->file_name_hi, Config::get('file_paths')['LETTERS_ISSUED_FILE_HI_PATH']);
            $data['file_name_hi'] = $uploadedHi['file_name'];
        }

        return $this->repository->create($data);
    }

    public function update(LettersIssuedDto $dto, $id)
    {
        $data = $dto->toArray();
        unset($data['created_by']);

        if ($dto->file_name) {
            $uploaded = $this->uploadFile($dto->file_name, Config::get('file_paths')['LETTERS_ISSUED_FILE_EN_PATH']);
            $data['file_name'] = $uploaded['file_name'];
        } else {
            unset($data['file_name']);
        }

        if ($dto->file_name_hi) {
            $uploadedHi = $this->uploadFile($dto->file_name_hi, Config::get('file_paths')['LETTERS_ISSUED_FILE_HI_PATH']);
            $data['file_name_hi'] = $uploadedHi['file_name'];
        } else {
            unset($data['file_name_hi']);
        }

        // Reset on update
        $data['is_approved'] = 0;
        $data['is_published'] = 0;
        $data['remarks'] = null;
        $data['publish_remark'] = null;

        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $data = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->repository->update($id, $data);
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
        return $this->repository->update($id, $data);
    }
}
