<?php

namespace App\Services;

use App\Repositories\LaboratoriesFileRepository;
use App\DTO\LabsPageFilesDto;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;


class LaboratoriesFileService
{
    use FileUploadTrait;
    private $laboratoriesFileRepository;

    public function __construct()
    {
        $this->laboratoriesFileRepository = new LaboratoriesFileRepository();
    }

    public function create(LabsPageFilesDto $labsPageFilesDto)
    {
        // PDF
        if ($labsPageFilesDto->file_name) {
            $file = $this->uploadFile($labsPageFilesDto->file_name, config('file_paths')['LABORATORIES_FILES_EN_PATH']);
            $labsPageFilesDto->file_name = $file['file_name'];
        }
        if ($labsPageFilesDto->file_name_hi) {
            $file = $this->uploadFile($labsPageFilesDto->file_name_hi, config('file_paths')['LABORATORIES_FILES_HI_PATH']);
            $labsPageFilesDto->file_name_hi = $file['file_name'];
        }

        return $this->laboratoriesFileRepository->create([
            'page_id' => $labsPageFilesDto->page_id,
            'title' => $labsPageFilesDto->title,
            'title_hi' => $labsPageFilesDto->title_hi,
            'description' => $labsPageFilesDto->description,
            'description_hi' => $labsPageFilesDto->description_hi,
            'file_name' => $labsPageFilesDto->file_name,
            'file_name_hi' => $labsPageFilesDto->file_name_hi,
            'created_by' => $labsPageFilesDto->created_by,
        ]);
    }


    public function findAllForPublic()
    {
        return $this->laboratoriesFileRepository->findAllForPublic();
    }

    public function findAll()
    {
        return $this->laboratoriesFileRepository->findAll();
    }

    public function findById($id)
    {
        return $this->laboratoriesFileRepository->findById($id);
    }

    public function update(LabsPageFilesDto $labsPageFilesDto, $id)
    {
        // PDF
        if ($labsPageFilesDto->file_name) {
            $file = $this->uploadFile($labsPageFilesDto->file_name, config('file_paths')['LABORATORIES_FILES_EN_PATH']);
            $labsPageFilesDto->file_name = $file['file_name'];
        }
        if ($labsPageFilesDto->file_name_hi) {
            $file = $this->uploadFile($labsPageFilesDto->file_name_hi, config('file_paths')['LABORATORIES_FILES_HI_PATH']);
            $labsPageFilesDto->file_name_hi = $file['file_name'];
        }
        $updateData = [
            'page_id' => $labsPageFilesDto->page_id,
            'title' => $labsPageFilesDto->title,
            'title_hi' => $labsPageFilesDto->title_hi,
            'date' => $labsPageFilesDto->date,
            'type' => $labsPageFilesDto->type,
            'description' => $labsPageFilesDto->description,
            'description_hi' => $labsPageFilesDto->description_hi,
            'file_name' => $labsPageFilesDto->file_name,
            'file_name_hi' => $labsPageFilesDto->file_name_hi,
            'created_by' => $labsPageFilesDto->created_by,
        ];
        $result = $this->laboratoriesFileRepository->update($updateData, $id);
        if (!$result) {
            return false;
        }
        return $result;
    }

    public function delete($id)
    {
        return $this->laboratoriesFileRepository->delete($id);
    }
}