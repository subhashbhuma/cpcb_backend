<?php

namespace App\Services;

use App\Repositories\PageRepository;
use App\DTO\PageDto;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Config;

class PageService
{
    use FileUploadTrait;
    private $pageRepository;

    public function __construct()
    {
        $this->pageRepository = new PageRepository();
    }

    public function findAll()
    {
        return $this->pageRepository->findAll();
    }

    public function findByMenuUrlForPublic($menuUrl)
    {
        return $this->pageRepository->findByMenuUrlForPublic($menuUrl);
    }

    public function findById($id)
    {
        return $this->pageRepository->findById($id);
    }

    public function create(PageDto $pageDto)
    {

        if ($pageDto->featured_image) {
            $file = $this->uploadFile($pageDto->featured_image, Config::get('file_paths')['PAGE_FEATURED_IMAGE_PATH']);
            $pageDto->featured_image = $file['file_name'];
        }
        $page = $this->pageRepository->create([
            'menu_id' => $pageDto->menu_id,
            'type' => $pageDto->type,
            'title' => $pageDto->title,
            'title_hi' => $pageDto->title_hi,
            'content' => $pageDto->content,
            'content_hi' => $pageDto->content_hi,
            'featured_image' => $pageDto->featured_image,
            'is_published' => $pageDto->is_published,
            'default_menu' => $pageDto->default_menu,
            'publish_remark' => $pageDto->publish_remark,
            'created_by' => $pageDto->created_by,
        ]);

        if (!$page) {
            return $page;
        }

        return $page;
    }

    public function update(PageDto $pageDto, $id)
    {
        if ($pageDto->featured_image !== null && $pageDto->featured_image instanceof UploadedFile) {
            $file = $this->uploadFile($pageDto->featured_image, Config::get('file_paths')['PAGE_FEATURED_IMAGE_PATH']);
            $pageDto->featured_image = $file['file_name'];
        }
        $updateData = [
            'menu_id' => $pageDto->menu_id,
            'type' => $pageDto->type,
            'title' => $pageDto->title,
            'title_hi' => $pageDto->title_hi,
            'content' => $pageDto->content,
            'content_hi' => $pageDto->content_hi,
            'is_published' => $pageDto->is_published,
            'default_menu' => $pageDto->default_menu,
            'publish_remark' => $pageDto->publish_remark,
            'created_by' => $pageDto->created_by,
            'updated_by' => $pageDto->updated_by,
        ];
        if ($pageDto->featured_image !== null) {
            $updateData['featured_image'] = $pageDto->featured_image;
        }
        $page = $this->pageRepository->update($updateData, $id);

        if (!$page) {
            return false;
        }

        return $this->findById($id);
    }

    public function delete($id)
    {
        return $this->pageRepository->delete($id);
    }

    public function approve(PageDto $pageDto, $id)
    {
        $updateData = [
            'is_approved' => $pageDto->is_approved,
            'remarks' => $pageDto->remarks,
            'updated_by' => $pageDto->updated_by,
        ];

        $result = $this->pageRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function publish(PageDto $pageDto, $id)
    {
        $updateData = [
            'is_approved' => $pageDto->is_approved,
            'is_published' => $pageDto->is_published,
            'remarks' => $pageDto->remarks,
            'publish_remark' => $pageDto->publish_remark,
            'updated_by' => $pageDto->updated_by,
        ];

        $result = $this->pageRepository->update($updateData, $id);

        if (!$result) {
            return false;
        }

        return $result;
    }

    public function findAllForPublic()
    {
        return $this->pageRepository->findAllForPublic();
    }
    public function findByUrlForPublic($url)
    {
        return $this->pageRepository->findByUrlForPublic($url);
    }
}
