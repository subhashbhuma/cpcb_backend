<?php

namespace App\Services;

use App\DTO\LabsPageDto;
use App\Repositories\LaboratoriesPageRepository;
use App\Traits\FileUploadTrait;

class LaboratoriesPageService
{
    use FileUploadTrait;
    private $laboratoriesPageRepository;

    public function __construct()
    {
        $this->laboratoriesPageRepository = new LaboratoriesPageRepository();
    }

    public function findAllForPublic()
    {
        return $this->laboratoriesPageRepository->findAllForPublic();
    }

    public function findAll()
    {
        return $this->laboratoriesPageRepository->findAll();
    }

    public function findAllByLab($catId)
    {
        return $this->laboratoriesPageRepository->findAllByLab($catId);
    }

    public function findById($id)
    {
        return $this->laboratoriesPageRepository->findById($id);
    }
    public function create(LabsPageDto $labsPageDto)
    {
        if ($labsPageDto->featured_image) {
            $file = $this->uploadFile($labsPageDto->featured_image, config('file_paths')['LABORATORIES_FEATURED_IMAGE_PATH']);
            $labsPageDto->featured_image = $file['file_name'];
        }
        $data = [
            'title' => $labsPageDto->title,
            'title_hi' => $labsPageDto->title_hi,
            'category_id' => $labsPageDto->category_id,
            'featured_image' => $labsPageDto->featured_image,
            'public_comments' => $labsPageDto->public_comments,
            'public_comments_hi' => $labsPageDto->public_comments_hi,
            'content' => $labsPageDto->content,
            'content_hi' => $labsPageDto->content_hi,

        ];

        return $this->laboratoriesPageRepository->create($data);
    }
    public function update(LabsPageDto $labsPageDto, $id)
    {
        if ($labsPageDto->featured_image) {
            $file = $this->uploadFile($labsPageDto->featured_image, config('file_paths')['LABORATORIES_FEATURED_IMAGE_PATH']);
            $labsPageDto->featured_image = $file['file_name'];
        }
        $data = [
            'title' => $labsPageDto->title,
            'title_hi' => $labsPageDto->title_hi,
            'category_id' => $labsPageDto->category_id,
            'featured_image' => $labsPageDto->featured_image,
            'public_comments' => $labsPageDto->public_comments,
            'public_comments_hi' => $labsPageDto->public_comments_hi,
            'content' => $labsPageDto->content,
            'content_hi' => $labsPageDto->content_hi,
        ];

        $result = $this->laboratoriesPageRepository->update($data, $id);
        if (!$result) {
            return false;
        }

        return $result;
    }
    public function delete($id)
    {
        return $this->laboratoriesPageRepository->delete($id);
    }

    public function approve(LabsPageDto $labsPageDto, $id)
    {
        $updateData = [
            'is_approved' => $labsPageDto->is_approved,
            'remarks' => $labsPageDto->remarks,
            'updated_by' => $labsPageDto->updated_by,
        ];

        $result = $this->laboratoriesPageRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function publish(LabsPageDto $labsPageDto, $id)
    {
        $updateData = [
            'is_approved' => $labsPageDto->is_approved,
            'is_published' => $labsPageDto->is_published,
            'remarks' => $labsPageDto->remarks,
            'updated_by' => $labsPageDto->updated_by,
        ];

        $result = $this->laboratoriesPageRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

}
