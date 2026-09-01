<?php

namespace App\Repositories;

use App\Models\Directory;

class DirectoryRepository
{
    /**
     * Get all directories for the admin panel.
     */
    public function findAll()
    {
        return Directory::with(['division', 'division_order'])
            ->orderBy('order_no', 'asc')
            ->orderBy('show_order', 'asc')
            ->get();
    }

    /**
     * Find a specific directory entry by ID.
     */
    public function findById($id)
    {
        return Directory::with(['division', 'division_order'])->findOrFail($id);
    }

    /**
     * Store a new directory entry.
     */
    public function create(array $data)
    {
        return Directory::create($data);
    }

    /**
     * Update an existing directory entry.
     */
    public function update(array $data, $id)
    {
        $directory = Directory::findOrFail($id);
        $directory->update($data);
        return $directory;
    }

    /**
     * Soft delete a directory entry.
     */
    public function delete($id)
    {
        $directory = Directory::findOrFail($id);
        return $directory->delete();
    }

    /**
     * Find published and approved directories for the public website.
     * Uses order_no and show_order for custom sorting.
     */
    public function findForPublic($limit = null)
    {
        $query = Directory::with(['division', 'division_order'])
            ->where('is_published', true)
            ->where('is_approved', 1)
            ->orderBy('order_no', 'asc')
            ->orderBy('show_order', 'asc')
            ->latest();

        if ($limit) {
            return $query->take($limit)->get();
        }

        return $query->get();
    }

    public function findPublished()
    {
        return Directory::with(['division', 'division_order'])
            ->where(['is_published' => 1, 'is_approved' => 1])
            ->orderBy('order_no', 'asc')
            ->orderBy('show_order', 'asc')
            ->get();
    }
}
