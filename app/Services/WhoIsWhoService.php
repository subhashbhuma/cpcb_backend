<?php

namespace App\Services;

use App\DTO\WhoIsWhoDto;
use App\Repositories\WhoIsWhoRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class WhoIsWhoService
{
    use FileUploadTrait;
    private $whoIsWhoRepository;

    public function __construct()
    {
        $this->whoIsWhoRepository = new WhoIsWhoRepository();
    }

    public function findAllForHomepage()
    {
        return $this->whoIsWhoRepository->findAllForHomepage();
    }

    public function findAllForWhoIsWho()
    {
        return $this->whoIsWhoRepository->findAllForWhoIsWho();
    }

    public function findByDesignation($designation)
    {
        return $this->whoIsWhoRepository->findByDesignation($designation);
    }

    public function whoIsWhoHomePage()
    {
        return $this->whoIsWhoRepository->whoIsWhoHomePage();
    }


    public function findAll()
    {
        return $this->whoIsWhoRepository->findAll();
    }

    public function findById($id)
    {
        return $this->whoIsWhoRepository->findById($id);
    }

    public function create(WhoIsWhoDto $whoIsWhoDto)
    {
        // Upload
        if ($whoIsWhoDto->image) {
            $file = $this->uploadFile($whoIsWhoDto->image, Config::get('file_paths')['WHO_IS_WHO_IMAGE_PATH']);
            $whoIsWhoDto->image = $file['file_name'];
        }

        $insertData = [
            'order' => $whoIsWhoDto->order,
            'name' => $whoIsWhoDto->name,
            'name_hi' => $whoIsWhoDto->name_hi,
            'designation' => $whoIsWhoDto->designation,
            'designation_hi' => $whoIsWhoDto->designation_hi,
            'mobile_number' => $whoIsWhoDto->mobile_number,
            'email_id' => $whoIsWhoDto->email_id,
            'division_id' => $whoIsWhoDto->division_id,
            'address' => $whoIsWhoDto->address,
            'address_hi' => $whoIsWhoDto->address_hi,
            'show_on_homepage' => $whoIsWhoDto->show_on_homepage,
            'hide_on_who_is_who' => $whoIsWhoDto->hide_on_who_is_who,
            'is_approved' => $whoIsWhoDto->is_approved,
            'is_published' => $whoIsWhoDto->is_published,
            'remarks' => $whoIsWhoDto->remarks,
            'publish_remark' => $whoIsWhoDto->publish_remark,
            'created_by' => $whoIsWhoDto->created_by,
            'updated_by' => $whoIsWhoDto->updated_by,
        ];

        if ($whoIsWhoDto->image) {
            $insertData['image'] = $whoIsWhoDto->image;
        }

        $result = $this->whoIsWhoRepository->create($insertData);

        if (!$result) {
            return false;
        }

        return $result;
    }


    public function update(WhoIsWhoDto $whoIsWhoDto, $id)
    {
        // Upload
        if ($whoIsWhoDto->image) {
            $file = $this->uploadFile($whoIsWhoDto->image, Config::get('file_paths')['WHO_IS_WHO_IMAGE_PATH']);
            $whoIsWhoDto->image = $file['file_name'];
        }

        $updateData = [
            'order' => $whoIsWhoDto->order,
            'name' => $whoIsWhoDto->name,
            'name_hi' => $whoIsWhoDto->name_hi,
            'designation' => $whoIsWhoDto->designation,
            'designation_hi' => $whoIsWhoDto->designation_hi,
            'mobile_number' => $whoIsWhoDto->mobile_number,
            'email_id' => $whoIsWhoDto->email_id,
            'division_id' => $whoIsWhoDto->division_id,
            'address' => $whoIsWhoDto->address,
            'address_hi' => $whoIsWhoDto->address_hi,
            'show_on_homepage' => $whoIsWhoDto->show_on_homepage,
            'hide_on_who_is_who' => $whoIsWhoDto->hide_on_who_is_who,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'updated_by' => $whoIsWhoDto->updated_by,
        ];

        if ($whoIsWhoDto->image) {
            $updateData['image'] = $whoIsWhoDto->image;
        }

        $result = $this->whoIsWhoRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function delete($id)
    {
        return $this->whoIsWhoRepository->delete($id);
    }

    public function approve(WhoIsWhoDto $whoIsWhoDto, $id)
    {
        $updateData = [
            'is_approved' => $whoIsWhoDto->is_approved,
            'remarks' => $whoIsWhoDto->remarks,
            'publish_remark' => $whoIsWhoDto->publish_remark,
            'updated_by' => $whoIsWhoDto->updated_by,
        ];

        $result = $this->whoIsWhoRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function publish(WhoIsWhoDto $whoIsWhoDto, $id)
    {
        $updateData = [
            'is_approved' => $whoIsWhoDto->is_approved,
            'is_published' => $whoIsWhoDto->is_published,
            'remarks' => $whoIsWhoDto->remarks,
            'publish_remark' => $whoIsWhoDto->publish_remark,
            'updated_by' => $whoIsWhoDto->updated_by,
        ];

        $result = $this->whoIsWhoRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }
}
