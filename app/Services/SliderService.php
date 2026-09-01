<?php

namespace App\Services;

use App\DTO\SliderDto;
use App\Repositories\SliderRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class SliderService
{
    use FileUploadTrait;
    private $sliderRepository;

    public function __construct()
    {
        $this->sliderRepository = new SliderRepository();
    }

    public function findForPublic()
    {
        return $this->sliderRepository->findForPublic();
    }

    public function findAll()
    {
        return $this->sliderRepository->findAll();
    }

    public function findById($id)
    {
        return $this->sliderRepository->findById($id);
    }

    public function create(SliderDto $sliderDto)
    {
        // Upload
        if ($sliderDto->file_name) {
            $file = $this->uploadFile($sliderDto->file_name, Config::get('file_paths')['SLIDER_IMAGE_PATH']);
            $sliderDto->file_name = $file['file_name'];
        }

        $result = $this->sliderRepository->create([
            'title' => $sliderDto->title,
            'title_hi' => $sliderDto->title_hi,
            'description' => $sliderDto->description,
            'description_hi' => $sliderDto->description_hi,
            'file_name' => $sliderDto->file_name,
            'link' => $sliderDto->link,
            'publish_remark' => $sliderDto->publish_remark,
            'created_by' => $sliderDto->created_by,
            'updated_by' => $sliderDto->updated_by,
        ]);

        if (!$result) {
            return false;
        }

        return $result;
    }


    public function update(SliderDto $sliderDto, $id)
    {
        // Upload
        if ($sliderDto->file_name) {
            $file = $this->uploadFile($sliderDto->file_name, Config::get('file_paths')['SLIDER_IMAGE_PATH']);
            $sliderDto->file_name = $file['file_name'];
        }

        $updateData = [
            'title' => $sliderDto->title,
            'title_hi' => $sliderDto->title_hi,
            'description' => $sliderDto->description,
            'description_hi' => $sliderDto->description_hi,
            'link' => $sliderDto->link,
            'is_approved' => 0,
            'is_published' => 0,
            'remarks' => null,
            'publish_remark' => null,
            'created_by' => $sliderDto->created_by,
            'updated_by' => $sliderDto->updated_by,
        ];

        if ($sliderDto->file_name) {
            $updateData['file_name'] = $sliderDto->file_name;
        }

        $result = $this->sliderRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function delete($id)
    {
        return $this->sliderRepository->delete($id);
    }

    public function approve(SliderDto $sliderDto, $id)
    {
        $updateData = [
            'is_approved' => $sliderDto->is_approved,
            'remarks' => $sliderDto->remarks,
            'publish_remark' => $sliderDto->publish_remark,
            'updated_by' => $sliderDto->updated_by,
        ];

        $result = $this->sliderRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function publish(SliderDto $sliderDto, $id)
    {
        $updateData = [
            'is_approved' => $sliderDto->is_approved,
            'is_published' => $sliderDto->is_published,
            'remarks' => $sliderDto->remarks,
            'publish_remark' => $sliderDto->publish_remark,
            'updated_by' => $sliderDto->updated_by,
        ];

        $result = $this->sliderRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }
}
