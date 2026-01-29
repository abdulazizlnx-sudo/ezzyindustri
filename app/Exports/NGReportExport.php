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

class NGReportExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    protected $reports;
    protected $startDate;
    protected $endDate;
    protected $machine;
    protected $shift;
    protected $status;
    protected $summary;
    protected $printedBy;

    public function __construct($reports, $startDate, $endDate, $machine, $shift, $status, $summary, $printedBy)
    {
        $this->reports = $reports;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->machine = $machine;
        $this->shift = $shift;
        $this->status = $status;
        $this->summary = $summary;
        $this->printedBy = $printedBy;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                
                // Insert rows for header
                $sheet->insertNewRowBefore(1, 8);
                
                // Set header values
                $sheet->setCellValue('A1', 'NG REPORT ANALYSIS');
                $sheet->setCellValue('A2', 'PT. EZZY INDUSTRI');
                $sheet->setCellValue('A3', 'Periode: ' . date('d/m/Y', strtotime($this->startDate)) . ' - ' . date('d/m/Y', strtotime($this->endDate)));
                $sheet->setCellValue('A4', 'Machine: ' . ($this->machine ?: 'All'));
                $sheet->setCellValue('A5', 'Shift: ' . ($this->shift ?: 'All'));
                $sheet->setCellValue('A6', 'Status: ' . ($this->status ?: 'All'));
                $sheet->setCellValue('A7', 'Total NG: ' . number_format($this->summary['total_ng']));
                $sheet->setCellValue('A8', 'Average NG %: ' . number_format($this->summary['avg_ng_percentage'], 2) . '%');

                // Merge cells for headers
                foreach(range(1,8) as $row) {
                    $sheet->mergeCells("A{$row}:H{$row}");
                }

                // Style headers
                $sheet->getStyle('A1:H8')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ]
                ]);

                // Style data
                $lastRow = $this->reports->count() + 9;
                $sheet->getStyle("A9:H{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                    ]
                ]);
            }
        ];
    }

    public function collection()
    {
        return $this->reports->map(function ($item) {
            return [
                'Date' => $item->date,
                'Machine' => $item->machine_name,
                'Product' => $item->product_name,
                'Operator' => $item->operator_name,
                'NG Type' => $item->ng_type,
                'Total NG' => $item->total_ng,
                'NG %' => $item->ng_percentage . '%',
                'Status' => ucfirst($item->status)
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Date',
            'Machine',
            'Product',
            'Operator',
            'NG Type',
            'Total NG',
            'NG %',
            'Status'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            9 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA']
                ]
            ]
        ];
    }
}