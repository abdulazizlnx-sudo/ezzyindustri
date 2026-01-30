<?php

namespace App\Livewire\Manajerial\Production;

use Livewire\Component;
use App\Models\Production;
use App\Models\Machine;
use App\Models\User;
use App\Models\Shift;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductionReportExport;

class ProductionReport extends Component
{
    public $dateFrom;
    public $dateTo;
    public $selectedMachine;
    public $selectedOperator;
    public $selectedShift;
    
    public function mount()
    {
        $this->dateFrom = now()->subMonths(3)->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function render()
    {
        $query = Production::query()
            ->with(['machine', 'shift', 'user', 'oeeRecord']) // Tambahkan eager loading
            ->where('status', 'finished')
            ->whereBetween('start_time', [$this->dateFrom, $this->dateTo]);
                
        if ($this->selectedMachine) {
            $query->where('machine_id', $this->selectedMachine);
        }
        
        if ($this->selectedOperator) {
            $query->where('user_id', $this->selectedOperator);
        }
        
        if ($this->selectedShift) {
            $query->where('shift_id', $this->selectedShift);
        }

        $productions = $query->get();
        $chartData = $this->prepareChartData($productions);
        
        // Dispatch chart data update for immediate rendering
        $this->dispatch('updateChartData', $chartData);
    
        return view('livewire.manajerial.production.production-report', [
            'productions' => $productions,
            'machines' => Machine::all(),
            'operators' => User::where('role', 'karyawan')->get(),
            'shifts' => Shift::all(),
            'chartData' => $chartData
        ]);
    }

    private function prepareChartData($productions)
    {
        $dates = [];
        $productionData = [];
        $targetData = [];
        $oeeData = [];
    
        foreach ($productions->groupBy(function($item) {
            return $item->start_time->format('d/m');
        }) as $date => $items) {
            $dates[] = $date;
            $productionData[] = $items->sum('total_production');
            $targetData[] = $items->sum('target_per_shift');
            $oeeData[] = round($items->avg('oeeRecord.oee_score'), 2);
        }
    
        return [
            'dates' => $dates,
            'production' => [
                [
                    'name' => 'Produksi',
                    'data' => $productionData
                ],
                [
                    'name' => 'Target',
                    'data' => $targetData
                ]
            ],
            'oee' => [
                [
                    'name' => 'OEE Score',
                    'data' => $oeeData
                ]
            ]
        ];
    }

    public function refreshData()
    {
        // Force refresh of data and charts
        $this->dispatch('dataRefreshed');
    }

    public function exportPDF()
    {
        $url = route('manajerial.production.report.pdf', [
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'machineId' => $this->selectedMachine,
            'operatorId' => $this->selectedOperator,
            'shiftId' => $this->selectedShift
        ]);

        // Open in new tab using JavaScript
        $this->dispatch('openNewTab', url: $url);
    }

    public function exportExcel()
    {
        $productions = $this->getFilteredProductions();
        $fileName = 'laporan-produksi-' . now()->format('d-m-Y-His') . '.xlsx';
        $printedBy = auth()->user()->name ?? 'System';

        return Excel::download(
            new ProductionReportExport(
                $productions, 
                $this->dateFrom, 
                $this->dateTo,
                $printedBy
            ), 
            $fileName
        );
    }

    private function getFilteredProductions()
    {
        return Production::with(['machine', 'shift', 'user', 'oeeRecord'])
            ->where('status', 'finished')
            ->whereBetween('start_time', [$this->dateFrom, $this->dateTo])
            ->when($this->selectedMachine, fn($q) => $q->where('machine_id', $this->selectedMachine))
            ->when($this->selectedOperator, fn($q) => $q->where('user_id', $this->selectedOperator))
            ->when($this->selectedShift, fn($q) => $q->where('shift_id', $this->selectedShift))
            ->get();
    }
}