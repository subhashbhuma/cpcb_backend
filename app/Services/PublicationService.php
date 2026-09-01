<?php

namespace App\Services;

use App\DTO\PublicationDto;
use App\Repositories\PublicationRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class PublicationService
{
    use FileUploadTrait;
    public $publicationRepository;

    public function __construct()
    {
        $this->publicationRepository = new PublicationRepository();
    }

    public function findForPublic($limit = 10)
    {
        return $this->publicationRepository->findForPublic($limit);
    }

    public function findByCategoryForPublic($categoryId)
    {
        return $this->publicationRepository->findByCategoryForPublic($categoryId);
    }

    public function findAll()
    {
        return $this->publicationRepository->findAll();
    }
    public function findById($id)
    {
        return $this->publicationRepository->findById($id);
    }
    public function create(PublicationDto $publicationDto)
    {
        if ($publicationDto->file_name) {
            $uploaded = $this->uploadFile($publicationDto->file_name, Config::get('file_paths')['PUBLICATION_FILE_EN_PATH']);
            $publicationDto->file_name = $uploaded['file_name'];
        }
        if ($publicationDto->file_name_hi) {
            $uploaded = $this->uploadFile($publicationDto->file_name_hi, Config::get('file_paths')['PUBLICATION_FILE_HI_PATH']);
            $publicationDto->file_name_hi = $uploaded['file_name'];
        }
        $result = $this->publicationRepository->create([
            'category_id' => $publicationDto->category_id,
            'title' => $publicationDto->title,
            'title_hi' => $publicationDto->title_hi,
            'price' => $publicationDto->price,
            'file_name' => $publicationDto->file_name,
            'file_name_hi' => $publicationDto->file_name_hi,
            'published_date' => $publicationDto->published_date,
            'is_approved' => $publicationDto->is_approved,
            'is_published' => $publicationDto->is_published,
            'remarks' => $publicationDto->remarks,
            'publish_remark' => $publicationDto->publish_remark,
            'created_by' => $publicationDto->created_by,
            'created_at' => $publicationDto->created_at,
            'updated_by' => $publicationDto->updated_by,
            'updated_at' => $publicationDto->updated_at,
        ]);

        if (!$result) {
            return false;
        }

        return $result;
    }
    public function update(PublicationDto $publicationDto, $id)
    {
        if ($publicationDto->file_name) {
            $uploaded = $this->uploadFile($publicationDto->file_name, Config::get('file_paths')['PUBLICATION_FILE_EN_PATH']);
            $publicationDto->file_name = $uploaded['file_name'];
        }
        if ($publicationDto->file_name_hi) {
            $uploaded = $this->uploadFile($publicationDto->file_name_hi, Config::get('file_paths')['PUBLICATION_FILE_HI_PATH']);
            $publicationDto->file_name_hi = $uploaded['file_name'];
        }
        $data = [
            'category_id' => $publicationDto->category_id,
            'title' => $publicationDto->title,
            'title_hi' => $publicationDto->title_hi,
            'price' => $publicationDto->price,
            'published_date' => $publicationDto->published_date,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'created_by' => $publicationDto->created_by,
            'created_at' => $publicationDto->created_at,
            'updated_by' => $publicationDto->updated_by,
            'updated_at' => $publicationDto->updated_at,
        ];


        if ($publicationDto->file_name) {
            $data['file_name'] = $publicationDto->file_name;
        }
        if ($publicationDto->file_name_hi) {
            $data['file_name_hi'] = $publicationDto->file_name_hi;
        }
        return $this->publicationRepository->update($data, $id);
    }
    public function delete($id)
    {
        return $this->publicationRepository->delete($id);
    }
    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $updateData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->publicationRepository->update($updateData, $id);
    }
    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null)
    {
        $updateData = [
            'is_published' => $isPublished,
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];
        return $this->publicationRepository->update($updateData, $id);
    }
}
