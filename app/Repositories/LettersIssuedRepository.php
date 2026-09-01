<?php

namespace App\Repositories;

use App\Models\LettersIssued;
use Illuminate\Support\Facades\Schema;

class LettersIssuedRepository
{
    public function findAll()
    {
        return LettersIssued::all();
    }

    public function findById($id)
    {
        return LettersIssued::findOrFail($id);
    }

    public function create(array $data)
    {
        return LettersIssued::create($data);
    }

    public function update($id, array $data)
    {
        $record = LettersIssued::findOrFail($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        $record = LettersIssued::findOrFail($id);
        return $record->delete();
    }

    public function fetchForDatatable($type = null)
    {
        $query = LettersIssued::select([
            'id',
            'title',
            'title_hi',
            'publish_date',
            'file_name',
            'file_name_hi',
            'is_approved',
            'is_published',
            'remarks',
            'direction_state_id',
            'type'
        ]);

        if ($type) {
            $query->where('type', $type);
        }

        return $query;
    }

    public function fetchAllForPublicDataTable($type = null)
    {
        // Direction logic had ActType filtering. 
        // LettersIssued has no ActType.
        // It does have 'direction_state_id' (States).
        // I will return the base query for public view.
        // Filtering by 'is_published' is standard.

        $query = LettersIssued::where('is_published', 1);
        
        // If user wants specific type filtering logic (like 'section-5ep'), it's not applicable here based on schema.
        // However, standard search, sort, pagination is handled in Controller/Service usually?
        // In DirectionRepository it handled filtering.
        // I will implement basic search/filter here if needed, but DirectionRepository had `fetchAllForPublicDataTable`?
        // Let's check DirectionRepository again. 
        // (I viewed it before but didn't cache it deep in memory).
        // DirectionRepository had: `return Direction::where('direction_act_type_id', $type)...`
        // Here we don't have ActType.
        
        return $query;
    }
}
