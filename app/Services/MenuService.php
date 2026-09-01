<?php

namespace App\Services;

use App\Repositories\MenuRepository;

class MenuService
{
    private $menuRepository;

    public function __construct()
    {
        $this->menuRepository = new MenuRepository();
    }

    public function findAll()
    {
        return $this->menuRepository->findAll();
    }

    public function findAllById($parentId)
    {
        return $this->menuRepository->findAllById($parentId);
    }

    public function findById($id)
    {
        return $this->menuRepository->findById($id);
    }


    public function delete($id)
    {
        return $this->menuRepository->delete($id);
    }

    public function findByUrlWithParents($url)
    {
        return $this->menuRepository->findByUrlWithParents($url);
    }

    public function fetchMainParent($parents)
    {
        $mainParent = '';
        foreach ($parents as $parent) {
            if ($parent->parent_id == null || $parent->parent_id == 0) {
                $mainParent = $parent;
            }
        }
        return $mainParent;
    }

    public function fetchMainParentName($parents)
    {
        $mainParent = '';
        foreach ($parents as $parent) {
            if ($parent->parent_id == null || $parent->parent_id == 0) {
                $mainParent = getLocalizedDataFromObj($parent, 'title');
            }
        }
        return $mainParent;
    }
}
