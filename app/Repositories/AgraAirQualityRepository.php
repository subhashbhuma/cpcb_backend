<?php

namespace App\Repositories;

use App\Models\AgraAirQuality;

class AgraAirQualityRepository
{
    /**
     * Get all agra air quality records
     */
    public function findAll()
    {
        return AgraAirQuality::with('qualityZone')->get();
    }

    public function getAllAgraAirQualitiesDataTable()
    {
        return AgraAirQuality::with('qualityZone');
    }

    /**
     * Find agra air quality by ID
     */
    public function findById($id)
    {
        return AgraAirQuality::with('qualityZone')->findOrFail($id);
    }

    /**
     * Create new agra air quality record
     */
    public function create(array $data)
    {
        return AgraAirQuality::create($data);
    }

    /**
     * Update agra air quality record
     */
    public function update(array $data, $id)
    {
        $agraAirQuality = $this->findById($id);
        $agraAirQuality->update($data);
        return $agraAirQuality->fresh();
    }

    /**
     * Delete agra air quality record
     */
    public function delete($id)
    {
        $agraAirQuality = $this->findById($id);
        return $agraAirQuality->delete();
    }

    /**
     * Get published records for public
     */
    public function findForPublic()
    {
        return AgraAirQuality::with('qualityZone')
            ->where('is_approved', 1)
            ->where('is_published', 1)
            ->orderBy('for_date', 'desc')
            ->get();
    }

    /**
     * Get published records with pagination
     */
    public function findForPublicWithPagination($perPage = 10)
    {
        return AgraAirQuality::with('qualityZone')
            ->where('is_approved', 1)
            ->where('is_published', 1)
            ->orderBy('for_date', 'desc')
            ->paginate($perPage);
    }
}
