<?php

namespace App\Livewire\Manajerial\Quality;

use Livewire\Component;
use App\Services\QualityAnalysisService;
use Illuminate\Support\Facades\Log;
use App\Models\NGReport;
use App\Exports\ParetoAnalysisExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class ParetoAnalysis extends Component
{
    public $startDate;
    public $endDate;
    public $selectedMachine;
    public $selectedProduct;
    public $selectedShift;
    public $machines = [];
    public $products = [];
    public $chartData;

    public function mount()
    {
        $this->startDate = now()->subMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        
        // Get unique machines and products
        $this->machines = NGReport::distinct('machine_name')->pluck('machine_name');
        $this->products = NGReport::distinct('product_name')->pluck('product_name');
        
        $this->updateChart();
    }

    public function updateChart()
    {
        $service = new QualityAnalysisService();
        $this->chartData = $service->getParetoData(
            $this->startDate,
            $this->endDate,
            $this->selectedMachine,
            $this->selectedProduct,
            $this->selectedShift
        );
        
        $this->dispatch('chartDataUpdated', $this->chartData->toArray());
    }

    public function render()
    {
        return view('livewire.manajerial.quality.pareto-analysis');
    }

    public function exportPdf()
    {
        $service = new QualityAnalysisService();
        $paretoData = $service->getParetoData(
            $this->startDate,
            $this->endDate,
            $this->selectedMachine,
            $this->selectedProduct,
            $this->selectedShift
        );
    
        session([
            'pareto_export_params' => [
                'paretoData' => $paretoData,
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
                'machine' => $this->selectedMachine,
                'product' => $this->selectedProduct,
                'shift' => $this->selectedShift
            ]
        ]);
    
        // Instead of redirect, dispatch an event
        $this->dispatch('openPdfInNewTab', route('pareto.pdf'));
    }

    public function updated($field)
    {
        if (in_array($field, ['startDate', 'endDate', 'selectedMachine', 'selectedProduct', 'selectedShift'])) {
            $this->updateChart();
        }
    }

    public function exportExcel()
    {
        return Excel::download(
            new ParetoAnalysisExport(
                $this->chartData,
                $this->startDate,
                $this->endDate,
                $this->selectedMachine,
                $this->selectedProduct,
                $this->selectedShift,
                Auth::user()->name
            ),
            'pareto-analysis.xlsx'
        );
    }
}
