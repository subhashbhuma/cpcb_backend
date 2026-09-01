<?php

namespace App\Services;

use App\Repositories\TenderCorrigendumRepository;
use App\DTO\TenderCorrigendumDto;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class TenderCorrigendumService
{
    use FileUploadTrait;
    private $tenderCorrigendumRepository;

    public function __construct()
    {
        $this->tenderCorrigendumRepository = new TenderCorrigendumRepository();
    }

    public function findAll()
    {
        return $this->tenderCorrigendumRepository->findAll();
    }

    public function findById($id)
    {
        return $this->tenderCorrigendumRepository->findById($id);
    }

    public function create(TenderCorrigendumDto $tenderCorrigendumDto)
    {
        // PDF
        if ($tenderCorrigendumDto->file_name) {
            $file = $this->uploadFile($tenderCorrigendumDto->file_name, Config::get('file_paths')['TENDER_CORRIGENDUM_FILE_EN_PATH']);
            $tenderCorrigendumDto->file_name = $file['file_name'];
        }
        if ($tenderCorrigendumDto->file_name_hi) {
            $file = $this->uploadFile($tenderCorrigendumDto->file_name_hi, Config::get('file_paths')['TENDER_CORRIGENDUM_FILE_HI_PATH']);
            $tenderCorrigendumDto->file_name_hi = $file['file_name'];
        }

        $result = $this->tenderCorrigendumRepository->create([
            'tender_id' => $tenderCorrigendumDto->tender_id,
            'title' => $tenderCorrigendumDto->title,
            'title_hi' => $tenderCorrigendumDto->title_hi,
            'description' => $tenderCorrigendumDto->description,
            'description_hi' => $tenderCorrigendumDto->description_hi,
            'file_name' => $tenderCorrigendumDto->file_name,
            'file_name_hi' => $tenderCorrigendumDto->file_name_hi,
            'created_by' => $tenderCorrigendumDto->created_by,
        ]);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function update(TenderCorrigendumDto $tenderCorrigendumDto, $id)
    {
        // PDF
        if ($tenderCorrigendumDto->file_name) {
            $file = $this->uploadFile($tenderCorrigendumDto->file_name, Config::get('file_paths')['TENDER_CORRIGENDUM_FILE_EN_PATH']);
            $tenderCorrigendumDto->file_name = $file['file_name'];
        }
        if ($tenderCorrigendumDto->file_name_hi) {
            $file = $this->uploadFile($tenderCorrigendumDto->file_name_hi, Config::get('file_paths')['TENDER_CORRIGENDUM_FILE_HI_PATH']);
            $tenderCorrigendumDto->file_name_hi = $file['file_name'];
        }

        $result = $this->tenderCorrigendumRepository->update([
            'tender_id' => $tenderCorrigendumDto->tender_id,
            'title' => $tenderCorrigendumDto->title,
            'title_hi' => $tenderCorrigendumDto->title_hi,
            'description' => $tenderCorrigendumDto->description,
            'description_hi' => $tenderCorrigendumDto->description_hi,
            'file_name' => $tenderCorrigendumDto->file_name,
            'file_name_hi' => $tenderCorrigendumDto->file_name_hi,
            'created_by' => $tenderCorrigendumDto->created_by,
            'updated_by' => $tenderCorrigendumDto->updated_by,
        ], $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function delete($id)
    {
        return $this->tenderCorrigendumRepository->delete($id);
    }
}
