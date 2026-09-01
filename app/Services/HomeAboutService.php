<?php

namespace App\Services;

use App\DTO\HomeAboutDto;
use App\Repositories\HomeAboutRepository;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class HomeAboutService
{
    use FileUploadTrait;
    private $homeAboutRepository;

    public function __construct()
    {
        $this->homeAboutRepository = new HomeAboutRepository();
    }

    public function findFirst()
    {
        return $this->homeAboutRepository->findFirst();
    }

    public function findForPublic()
    {
        return $this->homeAboutRepository->findForPublic();
    }

    public function findAll()
    {
        return $this->homeAboutRepository->findAll();
    }

    public function findById($id)
    {
        return $this->homeAboutRepository->findById($id);
    }

    public function update(HomeAboutDto $homeAboutDto, $id)
    {
        // Upload
        if ($homeAboutDto->image) {
            $file = $this->uploadFile($homeAboutDto->image, Config::get('file_paths')['HOME_ABOUT_IMAGE_PATH']);
            $homeAboutDto->image = $file['file_name'];
        }

        $updateData = [
            'title' => $homeAboutDto->title,
            'title_hi' => $homeAboutDto->title_hi,
            'description' => $homeAboutDto->description,
            'description_hi' => $homeAboutDto->description_hi,
            'button_link' => $homeAboutDto->button_link,
            'is_approved' => $homeAboutDto->is_approved,
            'is_published' => $homeAboutDto->is_published,
            'remarks' => $homeAboutDto->remarks,
            'updated_by' => $homeAboutDto->updated_by,
        ];


        if ($homeAboutDto->image) {
            $updateData['image'] = $homeAboutDto->image;
        }

        $result = $this->homeAboutRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function delete($id)
    {
        return $this->homeAboutRepository->delete($id);
    }

    public function approve(HomeAboutDto $homeAboutDto, $id)
    {
        $updateData = [
            'is_approved' => $homeAboutDto->is_approved,
            'remarks' => $homeAboutDto->remarks,
            'updated_by' => $homeAboutDto->updated_by,
        ];

        $result = $this->homeAboutRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function publish(HomeAboutDto $homeAboutDto, $id)
    {
        $updateData = [
            'is_approved' => $homeAboutDto->is_approved,
            'is_published' => $homeAboutDto->is_published,
            'remarks' => $homeAboutDto->remarks,
            'updated_by' => $homeAboutDto->updated_by,
        ];

        $result = $this->homeAboutRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }
}
