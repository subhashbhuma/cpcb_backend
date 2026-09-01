<?php

namespace App\Repositories;

use App\Models\Direction;

class DirectionRepository
{
    public function findAll()
    {
        return Direction::orderBy('id', 'DESC')->get();
    }
    public function fetchForDatatable()
    {
        return Direction::orderBy('id', 'DESC')->select('id', 'title', 'publish_date', 'is_approved', 'is_published', 'created_at', 'updated_at');
    }

    public function fetchAllForPublicDataTable($filters = [], $type = 'latest')
    {
        $baseQuery = Direction::with(['directionActType', 'directionType', 'directionSubject'])
            ->where('is_published', 1)
            ->where('is_approved', 1);

        // Define threshold (30 days ago, matching Tender logic)
        $thresholdDate = \Carbon\Carbon::now()->subDays(30)->startOfDay();

        $query = clone $baseQuery;

        if ($type === 'latest') {
            $query->where('publish_date', '>=', $thresholdDate);
        } elseif ($type === 'archive') {
            $query->where('publish_date', '<', $thresholdDate); // Use < for archive to avoid overlap
        }

        // Search
        if (!empty($filters['search'])) {
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $filters['search']);
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%" . strtolower($search) . "%"])
                  ->orWhereRaw('title_hi LIKE ?', ["%{$search}%"]);
            });
        }

        // Sorting
        $sortField = $filters['sort'] ?? 'publish_date';
        $sortOrder = $filters['order'] ?? 'desc';
        $query->orderBy($sortField, $sortOrder);

        // Pagination
        $perPage = (int) ($filters['per_page'] ?? 10);
        $results = $query->paginate($perPage);

        // Total count for this section (ignoring search filters)
        $totalForType = (clone $baseQuery)
            ->where('publish_date', $type === 'latest' ? '>=' : '<', $thresholdDate)
            ->count();

        return [
            'results' => $results,
            'totalForType' => $totalForType,
        ];
    }

    public function findById($id)
    {
        return Direction::find($id);
    }

    public function create($data)
    {
        return Direction::create($data);
    }

    public function update($data, $id)
    {
        $result = Direction::find($id);
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
        $result = Direction::find($id);
        if ($result) {
            return $result->delete();
        }
        return false;
    }

    public function findForPublicWithPagination($perPage = 10)
    {
        return Direction::where('is_published', 1)->orderBy('publish_date', 'desc')->paginate($perPage);
    }

    public function findForPublic($limit = null)
    {
        if ($limit) {
            return Direction::where('is_published', 1)->limit($limit)->orderBy('publish_date', 'desc')->get();
        }
        return Direction::where('is_published', 1)->orderBy('publish_date', 'desc')->get();
    }
}
