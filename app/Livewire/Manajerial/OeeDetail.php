<?php

namespace App\Livewire\Manajerial;

use App\Models\Machine;
use App\Models\OeeRecord;
use Carbon\Carbon;
use Livewire\Component;

class OeeDetail extends Component
{
    public $machine;
    public $startDate;
    public $endDate;
    public $selectedShift;
    public $averageAvailability;
    public $averagePerformance;
    public $averageQuality;
    public $oeeScore;
    public $lastUpdated;
    public $refreshInterval = 300000;

    public function mount($machineId, $startDate = null, $endDate = null, $selectedShift = null)
    {
        $this->machine = Machine::findOrFail($machineId);
        $this->startDate = $startDate ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = $endDate ?? Carbon::now()->format('Y-m-d');
        $this->selectedShift = $selectedShift;
        $this->loadData();
    }

    public function loadData()
    {
        $query = OeeRecord::where('machine_id', $this->machine->id)
            ->whereBetween('date', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ]);

        if ($this->selectedShift) {
            $query->where('shift_id', $this->selectedShift);
        }

        // Ambil data terbaru saja, bukan rata-rata
        $record = $query->latest('date')->first();

        if ($record) {
            $this->averageAvailability = $record->availability_rate;
            $this->averagePerformance = $record->performance_rate;
            $this->averageQuality = $record->quality_rate;
            $this->oeeScore = $record->oee_score;
        } else {
            $this->averageAvailability = 0;
            $this->averagePerformance = 0;
            $this->averageQuality = 0;
            $this->oeeScore = 0;
        }
        
        $this->lastUpdated = now()->format('H:i:s');
    }

    public function getChartData()
    {
        $records = OeeRecord::where('machine_id', $this->machine->id)
            ->whereBetween('date', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ])
            ->when($this->selectedShift, function($query) {
                return $query->where('shift_id', $this->selectedShift);
            })
            ->orderBy('date')
            ->get();
    
        // Ensure we have data and format it properly
        $chartData = [
            'labels' => $records->isNotEmpty() 
                ? $records->pluck('date')->map(fn($date) => Carbon::parse($date)->format('d/m/Y'))
                : ['No Data'],
            'availability' => $records->isNotEmpty() ? $records->pluck('availability_rate') : [0],
            'performance' => $records->isNotEmpty() ? $records->pluck('performance_rate') : [0],
            'quality' => $records->isNotEmpty() ? $records->pluck('quality_rate') : [0],
            'oee' => $records->isNotEmpty() ? $records->pluck('oee_score') : [0]
        ];
        
        // Log for debugging
        Log::info('Chart Data Generated', [
            'machine_id' => $this->machine->id,
            'records_count' => $records->count(),
            'chart_data' => $chartData
        ]);
        
        return $chartData;
    }

    public function render()
    {
        $chartData = $this->getChartData();
        $this->dispatch('updateChartData', $chartData);
        
        return view('livewire.manajerial.oee-detail', [
            'chartData' => $chartData,
            'machine' => $this->machine,
            'averageAvailability' => $this->averageAvailability,
            'averagePerformance' => $this->averagePerformance,
            'averageQuality' => $this->averageQuality,
            'oeeScore' => $this->oeeScore,
            'lastUpdated' => $this->lastUpdated,
        ]);
    }
}