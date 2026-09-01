<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PageFilesFormatExport implements FromArray, WithHeadings
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
            'id',
            'page_id',
            'title',
            'title_hi',
            'upload_date',
            'order_number',
            'file_name',
            'file_name_hi',
        ];
    }
}
