<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ParetoAnalysisExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    protected $paretoData;
    protected $startDate;
    protected $endDate;
    protected $machine;
    protected $product;
    protected $shift;
    protected $printedBy;

    public function __construct($paretoData, $startDate, $endDate, $machine, $product, $shift, $printedBy)
    {
        $this->paretoData = $paretoData;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->machine = $machine;
        $this->product = $product;
        $this->shift = $shift;
        $this->printedBy = $printedBy;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                
                $sheet->insertNewRowBefore(1, 6);
                
                $sheet->setCellValue('A1', 'PARETO ANALYSIS - NG TYPES');
                $sheet->setCellValue('A2', 'PT. EZZY INDUSTRI');
                $sheet->setCellValue('A3', 'Periode: ' . date('d/m/Y', strtotime($this->startDate)) . ' - ' . date('d/m/Y', strtotime($this->endDate)));
                $sheet->setCellValue('A4', 'Machine: ' . ($this->machine ?: 'All'));
                $sheet->setCellValue('A5', 'Product: ' . ($this->product ?: 'All'));
                $sheet->setCellValue('A6', 'Shift: ' . ($this->shift ?: 'All'));

                // Merge cells and style headers
                foreach(range(1,6) as $row) {
                    $sheet->mergeCells("A{$row}:D{$row}");
                }

                $sheet->getStyle('A1:D6')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ]
                ]);

                // Style the data
                $lastRow = count($this->paretoData) + 7;
                $sheet->getStyle("A7:D{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                    ]
                ]);
            }
        ];
    }

    public function collection()
    {
        return collect($this->paretoData)->map(function ($item) {
            return [
                'NG Type' => $item['defect'],
                'Count' => $item['count'],
                'Percentage' => $item['percentage'] . '%',
                'Cumulative' => $item['cumulative'] . '%'
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NG Type',
            'Count',
            'Percentage',
            'Cumulative'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            7 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA']
                ]
            ]
        ];
    }
}