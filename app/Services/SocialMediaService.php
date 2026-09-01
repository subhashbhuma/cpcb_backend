<?php

namespace App\Services;

use App\DTO\SocialMediaDto;
use App\Repositories\SocialMediaRepository;
use App\Traits\FileUploadTrait;

class SocialMediaService
{
    use FileUploadTrait;
    private $socialMediaRepository;

    public function __construct()
    {
        $this->socialMediaRepository = new SocialMediaRepository();
    }

    public function findForPublic()
    {
        return $this->socialMediaRepository->findForPublic();
    }

    public function findAll()
    {
        return $this->socialMediaRepository->findAll();
    }

    public function findById($id)
    {
        return $this->socialMediaRepository->findById($id);
    }

    public function create(SocialMediaDto $socialMediaDto)
    {
        return $this->socialMediaRepository->create([
            'type' => $socialMediaDto->type,
            'name' => $socialMediaDto->name,
            'url' => $socialMediaDto->url,
            'embed_code' => $socialMediaDto->embed_code,
            'icon_class' => $socialMediaDto->icon_class,
            'is_approved' => $socialMediaDto->is_approved,
            'is_published' => $socialMediaDto->is_published,
            'remarks' => $socialMediaDto->remarks,
            'created_by' => $socialMediaDto->created_by,
            'updated_by' => $socialMediaDto->updated_by,
        ]);
    }

    public function update(SocialMediaDto $socialMediaDto, $id)
    {
        $data = [
            'type' => $socialMediaDto->type,
            'name' => $socialMediaDto->name,
            'url' => $socialMediaDto->url,
            'embed_code' => $socialMediaDto->embed_code,
            'icon_class' => $socialMediaDto->icon_class,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'updated_by' => $socialMediaDto->updated_by,
        ];

        return $this->socialMediaRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->socialMediaRepository->delete($id);
    }

    public function approve($id, $isApproved, $remarks, $publishRemark = null)
    {
        $updateData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->socialMediaRepository->update($updateData, $id);
    }

    public function publish($id, $isApproved, $remarks, $isPublished, $publishRemark = null)
    {
        $updateData = [
            'is_approved' => $isApproved,
            'remarks' => $remarks ? strip_tags($remarks) : null,
            'is_published' => $isPublished,
            'publish_remark' => $publishRemark ? strip_tags($publishRemark) : null,
            'updated_by' => auth()->id(),
        ];

        return $this->socialMediaRepository->update($updateData, $id);
    }
}
