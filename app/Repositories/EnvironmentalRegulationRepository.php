<?php

namespace App\Repositories;

use App\Models\EnvironmentalRegulation;

class EnvironmentalRegulationRepository
{
    public function findForPublic($limit = 10)
    {
        return EnvironmentalRegulation::where('is_published', 1)->limit($limit)->orderBy('id', 'asc')->get();
    }
    public function findPublished()
    {
        return EnvironmentalRegulation::where(['is_published' => 1, 'is_approved' => 1])->orderBy('id', 'desc')->get();
    }

    public function findAll()
    {
        return EnvironmentalRegulation::orderBy('id', 'desc')
            ->get();
    }
    public function findById($id)
    {
        return EnvironmentalRegulation::find($id);
    }

    public function create($data)
    {
        return EnvironmentalRegulation::create($data);
    }

    public function update($data, $id)
    {
        $result = EnvironmentalRegulation::find($id);
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
        $result = EnvironmentalRegulation::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }
}
