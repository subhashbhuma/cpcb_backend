<?php

namespace App\Repositories;

use App\Models\EnvironmentalRegulationDetail;

class EnvironmentalRegulationDetailRepository
{
    /* -----------------------------
     | Fetch
     |-----------------------------*/

    public function findAll()
    {
        return EnvironmentalRegulationDetail::with([
            'environmentalRegulation',
            'parent',
        ])
            ->orderBy('order', 'asc')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function findAllForPublic()
    {
        return EnvironmentalRegulationDetail::with([
            'environmentalRegulation',
            'parent',
        ])
            ->where('is_published', 1)
            ->where('is_approved', 1)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function findForPublicByRegulationId($regulationId, $limit)
    {
        return EnvironmentalRegulationDetail::with('children')
            ->where('environmental_regulation_id', $regulationId)
            ->whereNull('parent_id')
            ->where('is_published', 1)
            ->limit($limit)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'desc')
            ->get();
    }



    public function findByRegulationIdForPublic($regulationId)
    {
        return EnvironmentalRegulationDetail::with([
            'environmentalRegulation',
            'parent',
            'children'
        ])
            ->where('environmental_regulation_id', $regulationId)
            ->whereNull('parent_id')
            ->where('is_published', 1)
            ->where('is_approved', 1)
            ->orderBy('order', 'asc')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function findByUrlForPublic($url)
    {
        return EnvironmentalRegulationDetail::where('url', $url)->where('is_published', 1)->orderBy('order', 'asc')->orderBy('id', 'desc')->get();
    }



    public function findById($id)
    {
        return EnvironmentalRegulationDetail::with([
            'environmentalRegulation',
            'parent',
        ])->find($id);
    }


    /* -----------------------------
     | Create / Update
     |-----------------------------*/

    public function create($data)
    {
        return EnvironmentalRegulationDetail::create($data);
    }

    public function update($data, $id)
    {
        $result = EnvironmentalRegulationDetail::find($id);

        if ($result) {
            $updated = $result->update($data);

            if (!$updated) {
                return false;
            }

            return $updated;
        }

        return false;
    }

    /* -----------------------------
     | Delete
     |-----------------------------*/

    public function delete($id)
    {
        $result = EnvironmentalRegulationDetail::find($id);

        if ($result) {
            return $result->delete(); // soft delete
        }

        return false;
    }
}
