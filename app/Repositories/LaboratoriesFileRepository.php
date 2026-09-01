<?php

namespace App\Repositories;

use App\Models\LaboratoriesFile;

class LaboratoriesFileRepository
{
    public function create($data)
    {
        return LaboratoriesFile::create($data);
    }
    public function findAllForPublic()
    {
        return LaboratoriesFile::where('is_published', 1)
            ->orderBy('id', 'asc')
            ->get();
    }
    public function findAll()
    {
        return LaboratoriesFile::with(['page'])->orderBy('id', 'asc')->get();
    }
    public function findById($id)
    {
        return LaboratoriesFile::find($id);
    }
    public function update($data, $id)
    {
        $result = LaboratoriesFile::find($id);
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
        $result = LaboratoriesFile::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}