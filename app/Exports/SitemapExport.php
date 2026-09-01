<?php

namespace App\Exports;

use App\Http\Controllers\Secure\SitemapController;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class SitemapExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    private int $slNo = 0;
    private $menus;

    public function __construct()
    {
        $this->menus = SitemapController::getFlattenedMenus();
    }

    public function collection()
    {
        return $this->menus;
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'Title',
            'URL',
        ];
    }

    public function map($menu): array
    {
        $this->slNo++;

        // Indent title based on depth to show hierarchy
        $prefix = str_repeat('— ', $menu->depth ?? 0);

        return [
            $this->slNo,
            $prefix . $menu->title,
            $menu->full_url ?? '',
        ];
    }

    /**
     * Make URL cells clickable hyperlinks in Excel.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                for ($row = 2; $row <= $highestRow; $row++) {
                    $cellValue = $sheet->getCell("C{$row}")->getValue();

                    if (!empty($cellValue) && str_starts_with($cellValue, 'http')) {
                        $sheet->getCell("C{$row}")->getHyperlink()->setUrl($cellValue);
                        $sheet->getStyle("C{$row}")->applyFromArray([
                            'font' => [
                                'color' => ['rgb' => '0563C1'],
                                'underline' => true,
                            ],
                        ]);
                    }
                }

                // Auto-size columns
                $sheet->getColumnDimension('A')->setWidth(8);
                $sheet->getColumnDimension('B')->setWidth(40);
                $sheet->getColumnDimension('C')->setWidth(60);
            },
        ];
    }
}
