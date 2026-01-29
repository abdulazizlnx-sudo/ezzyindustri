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

class ProductionReportExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    protected $productions;
    protected $dateFrom;
    protected $dateTo;
    protected $printedBy;

    public function __construct($productions, $dateFrom, $dateTo, $printedBy)
    {
        $this->productions = $productions;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->printedBy = $printedBy;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                
                // Insert 5 rows at the top
                $sheet->insertNewRowBefore(1, 5);
                
                // Add headers
                $sheet->setCellValue('A1', 'LAPORAN PRODUKSI');
                $sheet->setCellValue('A2', 'PT. EZZY INDUSTRI');
                $sheet->setCellValue('A3', 'Periode: ' . date('d/m/Y', strtotime($this->dateFrom)) . ' - ' . date('d/m/Y', strtotime($this->dateTo)));
                $sheet->setCellValue('A4', 'Dicetak pada: ' . now()->format('d/m/Y H:i'));
                $sheet->setCellValue('A5', 'Dicetak oleh: ' . $this->printedBy);

                // Merge cells for headers
                $sheet->mergeCells('A1:I1');
                $sheet->mergeCells('A2:I2');
                $sheet->mergeCells('A3:I3');
                $sheet->mergeCells('A4:I4');
                $sheet->mergeCells('A5:I5');

                // Style all headers
                $sheet->getStyle('A1:I5')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'font' => [
                        'bold' => true
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                // Special style for title
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E2EFDA']
                    ]
                ]);

                // Company name style
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'size' => 14
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F2F2F2']
                    ]
                ]);

                // Add borders and styling to the data
                $lastRow = $this->productions->count() + 6;
                $sheet->getStyle('A6:I' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Style the table header
                $sheet->getStyle('A6:I6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E2EFDA'],
                    ],
                ]);

                // Center align specific columns
                $sheet->getStyle('A:B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('F:I')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }

    public function collection()
    {
        return $this->productions->map(function ($production) {
            return [
                'Tanggal' => $production->start_time->format('d/m/Y'),
                'Shift' => $production->shift->name ?? 'Tidak Ada',
                'Operator' => $production->user->name ?? 'Tidak Ada',
                'Mesin' => $production->machine ?? 'Tidak Ada',
                'Produk' => $production->product,
                'Target' => $production->target_per_shift,
                'Hasil' => $production->total_production,
                'Defect' => $production->defect_count,
                'OEE Score' => ($production->oeeRecord->oee_score ?? 'Tidak Ada') . '%'
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tanggal', 'Shift', 'Operator', 'Mesin', 'Produk', 
            'Target', 'Hasil', 'Defect', 'OEE Score'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Default styles for all cells
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 14
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ],
            // Header row styles
            6 => [
                'font' => [
                    'bold' => true
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA']
                ]
            ]
        ];
    }
}