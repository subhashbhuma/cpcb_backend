<?php

namespace App\Repositories;

use App\Models\LaboratoriesCategory;
use Illuminate\Support\Facades\Log;

class LaboratoriesCategoryRepository
{
    public function findForPublic()
    {
        return LaboratoriesCategory::where('is_published', 1)->orderBy('id', 'desc')->get();
    }

    public function findAllForPublic()
    {
        return LaboratoriesCategory::where('is_published', 1)->orderBy('id', 'asc')->get();
    }

    public function findAll()
    {
        return LaboratoriesCategory::get();
    }

    public function findById($id)
    {
        return LaboratoriesCategory::where('id', $id)->orderBy('id', 'asc')->first();
    }

    public function create($data)
    {
        return LaboratoriesCategory::create($data);
    }

    public function update($data, $id)
{
    $model = LaboratoriesCategory::find($id);
    if (!$model) {
        Log::error("Update failed: ID {$id} not found.");
        return false;
    }

    $success = $model->update($data);
    if (!$success) {
        Log::error("Update failed for ID {$id}. Data: " . json_encode($data));
    }

    return $success;
}

    public function delete($id)
    {
        $result = LaboratoriesCategory::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }

    public function findAllLabsByCategory($categoryId)
    {
        return LaboratoriesCategory::where('id', $categoryId)
            ->with(['laboratories_page' => function ($query) {
                $query->where('is_published', 1)->with('laboratories_file');
            }])->first();
    }
}
