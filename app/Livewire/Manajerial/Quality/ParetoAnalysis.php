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
        // Check if dates are in query string for URL persistence
        if (request()->has('start_date')) {
            $this->startDate = request()->get('start_date');
        } else {
            $this->startDate = now()->subMonth()->format('Y-m-d');
        }
        
        if (request()->has('end_date')) {
            $this->endDate = request()->get('end_date');
        } else {
            $this->endDate = now()->format('Y-m-d');
        }
        
        // Get filter parameters from URL if present
        $this->selectedMachine = request()->get('machine', '');
        $this->selectedProduct = request()->get('product', '');
        $this->selectedShift = request()->get('shift', '');
        
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
        // Ensure chart data is available on render
        if (!$this->chartData) {
            $this->updateChart();
        }
        
        return view('livewire.manajerial.quality.pareto-analysis');
    }

    public function refreshData()
    {
        $this->updateChart();
        $this->dispatch('dataRefreshed');
    }

    public function exportPdf()
    {
        // Dispatch event to trigger PDF export with current parameters
        $this->dispatch('exportPdf');
    }

    public function getChartData()
    {
        if (!$this->chartData) {
            $this->updateChart();
        }
        return $this->chartData ? $this->chartData->toArray() : [];
    }

    public function updated($field)
    {
        if (in_array($field, ['startDate', 'endDate', 'selectedMachine', 'selectedProduct', 'selectedShift'])) {
            $this->updateChart();
            // Update URL to reflect current filters
            $this->updateUrl();
        }
    }
    
    private function updateUrl()
    {
        $params = [];
        
        if ($this->startDate) {
            $params['start_date'] = $this->startDate;
        }
        if ($this->endDate) {
            $params['end_date'] = $this->endDate;
        }
        if ($this->selectedMachine) {
            $params['machine'] = $this->selectedMachine;
        }
        if ($this->selectedProduct) {
            $params['product'] = $this->selectedProduct;
        }
        if ($this->selectedShift) {
            $params['shift'] = $this->selectedShift;
        }
        
        $queryString = !empty($params) ? '?' . http_build_query($params) : '';
        $this->dispatch('urlUpdated', $queryString);
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
