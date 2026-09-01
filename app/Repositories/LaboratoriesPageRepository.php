<?php

namespace App\Repositories;

use App\Models\LaboratoriesCategory;
use App\Models\LaboratoriesPage;

class LaboratoriesPageRepository
{
    public function create($data)
    {
        return LaboratoriesPage::create($data);
    }
    public function findAllForPublic()
    {
        return LaboratoriesPage::with(['files', 'category'])->where('is_published', 1)
            ->orderBy('id', 'asc')
            ->get();
    }
    public function findAll()
    {
        return LaboratoriesPage::with(['files', 'category'])->orderBy('id', 'asc')->get();
    }
    public function findAllByLab($catId)
    {
        return LaboratoriesPage::with(['files', 'category'])->where('category_id', $catId)
        ->where('is_published', 1)
        ->orderBy('id', 'asc')
        ->get();
    }
    public function findById($id)
    {
        return LaboratoriesPage::with(['files', 'category'])->find($id);
    }
    public function update($data, $id)
    {
        $result = LaboratoriesPage::find($id);
        if ($result) {
            $result = $result->update($data);
            if (!$result) {
                return false;
            }
            return $result;
        }
        return false;
    }
    public function delete($id)
    {
        $result = LaboratoriesPage::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
