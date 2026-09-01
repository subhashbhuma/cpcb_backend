<?php

namespace App\Repositories;

use App\Models\InformationCenterDetail;

class InformationCenterDetailRepository
{
    public function findAll()
    {
        return InformationCenterDetail::with('informationCenter')->latest()->get();
    }

    public function findById($id)
    {
        return InformationCenterDetail::findOrFail($id);
    }

    public function findByInformationCenterId($centerId)
    {
        return InformationCenterDetail::where('information_center_id', $centerId)
            ->latest()
            ->get();
    }

    public function findByInformationCenterIdForPublic($centerId)
    {
        return InformationCenterDetail::where('information_center_id', $centerId)
            ->where('is_published', 1)
            ->where('is_approved', 1)
            ->orderBy('title')
            ->get();
    }

    public function findByUrlForPublic($url)
    {
        $decodedUrl = base64_decode($url);
        return InformationCenterDetail::where('url', $decodedUrl)
            ->where('is_published', 1)
            ->where('is_approved', 1)
            ->get();
    }

    public function create(array $data): InformationCenterDetail
    {
        return InformationCenterDetail::create($data);
    }

    public function update($id, array $data): InformationCenterDetail
    {
        $detail = $this->findById($id);
        $detail->update($data);
        return $detail;
    }

    public function delete($id): bool
    {
        return InformationCenterDetail::destroy($id) > 0;
    }

    public function paginate($limit = 15)
    {
        return InformationCenterDetail::with('informationCenter')
            ->latest()
            ->paginate($limit);
    }
}
