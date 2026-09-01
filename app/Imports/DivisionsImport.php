<?php

namespace App\Imports;

use App\Models\Division;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DivisionsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        try {
            // Log the entire parsed row array so we can inspect the exact headers and values
            Log::info('Importing parsed row data:', $row);

            // We must have a title to insert
            if (empty($row['title_english']) && empty($row['title'])) {
                Log::warning('Skipping row due to empty title', ['row' => $row]);
                return null;
            }

            $title = !empty($row['title_english']) ? $row['title_english'] : ($row['title'] ?? 'Unknown Title');
 
            return new Division([
                'title'        => $title,
                'title_hi'     => $row['title_hindi'] ?? null,
                'is_new'       => $row['is_old']==0 ? 1 : 0,
                'is_approved'  => 1,
                'is_published' => 1,
                'created_by'   => Auth::id() ?? 1,
                'updated_by'   => Auth::id() ?? 1,
            ]);
        } catch (\Exception $e) {
            Log::error('Error importing Division row: ' . $e->getMessage(), ['row' => $row]);
            return null;
        }
    }
}
