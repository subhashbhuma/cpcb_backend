<?php

namespace App\Repositories;

use App\Models\HeadOffice;

class HeadOfficeRepository
{
    public function findAll()
    {
        return HeadOffice::with('division')->orderBy('order', 'asc')->latest()->get();
    }

    public function findById($id)
    {
        return HeadOffice::with(['division', 'personnels', 'profileActivities'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return HeadOffice::create($data);
    }

    public function update(array $data, $id)
    {
        $headOffice = HeadOffice::findOrFail($id);
        $headOffice->update($data);
        return $headOffice;
    }

    public function delete($id)
    {
        $headOffice = HeadOffice::findOrFail($id);
        return $headOffice->delete();
    }
    
    public function findForPublic($limit = null)
    {
        $query = HeadOffice::where('is_published', true)
            ->where('is_approved', 1)
            ->orderBy('order', 'asc')
            ->latest()
            ->with(['division', 'active_personnels', 'active_profile_activities']);

        if ($limit) {
            return $query->take($limit)->get();
        }

        return $query->get();
    }
}

