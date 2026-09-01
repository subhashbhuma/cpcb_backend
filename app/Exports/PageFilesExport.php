<?php

namespace App\Exports;

use App\Models\PageFile;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PageFilesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $pageId;

    public function __construct($pageId)
    {
        $this->pageId = $pageId;
    }

    public function collection()
    {
        return PageFile::where('page_id', $this->pageId)
            ->ordered()
            ->get();
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

    public function map($pageFile): array
    {
        return [
            $pageFile->id,
            $pageFile->page_id,
            $pageFile->title,
            $pageFile->title_hi,
            $pageFile->upload_date,
            $pageFile->order_number ?? 0,
            $pageFile->file_name,
            $pageFile->file_name_hi,
        ];
    }
}
