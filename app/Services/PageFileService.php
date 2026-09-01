<?php

namespace App\Services;

use App\Repositories\PageFileRepository;
use App\DTO\PageFileDto;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\UploadedFile;

class PageFileService
{
    use FileUploadTrait;
    private $pageFileRepository;

    public function __construct()
    {
        $this->pageFileRepository = new PageFileRepository();
    }

    public function findAll()
    {
        return $this->pageFileRepository->findAll();
    }

    public function findById($id)
    {
        return $this->pageFileRepository->findById($id);
    }

    public function create(PageFileDto $pageFileDto)
    {
        // PDF
        if ($pageFileDto->file_name) {
            $file = $this->uploadFile($pageFileDto->file_name, Config::get('file_paths')['PAGE_FILE_EN_PATH']);
            $pageFileDto->file_name = $file['file_name'];
        }
        if ($pageFileDto->file_name_hi) {
            $file = $this->uploadFile($pageFileDto->file_name_hi, Config::get('file_paths')['PAGE_FILE_HI_PATH']);
            $pageFileDto->file_name_hi = $file['file_name'];
        }

        $page = $this->pageFileRepository->create([
            'page_id' => $pageFileDto->page_id,
            'title' => $pageFileDto->title,
            'title_hi' => $pageFileDto->title_hi,
            'file_name' => $pageFileDto->file_name,
            'file_name_hi' => $pageFileDto->file_name_hi,
            'order_number' => $pageFileDto->order_number ?? 0,
            'upload_date' => $pageFileDto->upload_date,
            'created_by' => $pageFileDto->created_by,
        ]);

        if (!$page) {
            return false;
        }

        return $page;
    }

    public function update(PageFileDto $pageFileDto, $id)
    {
        // PDF
        if ($pageFileDto->file_name && $pageFileDto->file_name instanceof UploadedFile) {
            $file = $this->uploadFile($pageFileDto->file_name, Config::get('file_paths')['PAGE_FILE_EN_PATH']);
            $pageFileDto->file_name = $file['file_name'];
        }
        if ($pageFileDto->file_name_hi && $pageFileDto->file_name_hi instanceof UploadedFile) {
            $file = $this->uploadFile($pageFileDto->file_name_hi, Config::get('file_paths')['PAGE_FILE_HI_PATH']);
            $pageFileDto->file_name_hi = $file['file_name'];
        }

        $page = $this->pageFileRepository->update([
            'page_id' => $pageFileDto->page_id,
            'title' => $pageFileDto->title,
            'title_hi' => $pageFileDto->title_hi,
            'file_name' => $pageFileDto->file_name,
            'file_name_hi' => $pageFileDto->file_name_hi,
            'order_number' => $pageFileDto->order_number ?? 0,
            'upload_date' => $pageFileDto->upload_date,
            'created_by' => $pageFileDto->created_by,
            'updated_by' => $pageFileDto->updated_by,
        ], $id);

        if (!$page) {
            return false;
        }

        return $page;
    }

    public function delete($id)
    {
        return $this->pageFileRepository->delete($id);
    }
}
