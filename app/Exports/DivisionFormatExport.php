<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DivisionFormatExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            // Return an empty array since we only want the headers
            []
        ];
    }

    public function headings(): array
    {
        return [
            'S.No.',
            'division_id',
            'title_english',
            'title_hindi',
            'is_new'
        ];
    }
}
