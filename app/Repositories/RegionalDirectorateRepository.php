<?php

namespace App\Repositories;

use App\Models\RegionalDirectorate;
use Illuminate\Database\Eloquent\Collection;

class RegionalDirectorateRepository
{
    public function create(array $data): RegionalDirectorate
    {
        return RegionalDirectorate::create($data);
    }

    public function update(array $data, $id): bool
    {
        $record = RegionalDirectorate::find($id);
        if ($record) {
            return $record->update($data);
        }
        return false;
    }

    public function delete($id): bool
    {
        return RegionalDirectorate::destroy($id);
    }

    public function findById($id): ?RegionalDirectorate
    {
        return RegionalDirectorate::find($id);
    }

    public function findAll()
    {
        return RegionalDirectorate::orderBy('order', 'asc')->latest()->get();
    }

    public function findForPublic()
    {
        return RegionalDirectorate::where('is_published', 1)
            ->where('is_approved', 1)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->get();
    }
}
