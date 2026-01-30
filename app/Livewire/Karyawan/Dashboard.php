<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;
use App\Models\Production;
use App\Models\ProductionDowntime;
use App\Models\ProductionProblem;
use App\Models\OeeRecord;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Dashboard extends Component
{
    public $todayProduction;
    public $todayDefects;
    public $activeProduction;
    public $totalDowntime;
    public $recentDowntimes;
    public $recentProblems;
    public $qualityRate = 0; // Initialize with default value
    
    // Tambah property baru
    public $selectedPeriod = 'today';
    public $startDate;
    public $endDate;
    public $oeeData;
    public $productionTarget;
    public $productionRealization;
    public $performanceData;

    public function mount()
    {
        $this->setPeriod($this->selectedPeriod);
        $this->loadDashboardData();
        
        // Debug logging
        Log::info('Dashboard mounted', [
            'selectedPeriod' => $this->selectedPeriod,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'todayProduction' => $this->todayProduction,
            'todayDefects' => $this->todayDefects,
            'qualityRate' => $this->qualityRate ?? 'not_set'
        ]);
    }

    public function setPeriod($period)
    {
        $this->selectedPeriod = $period;
        
        // Use actual current date instead of fixed date
        $currentDate = Carbon::now();
        
        switch($period) {
            case 'today':
                $this->startDate = $currentDate->copy()->startOfDay();
                $this->endDate = $currentDate->copy()->endOfDay();
                break;
            case 'week':
                $this->startDate = $currentDate->copy()->startOfWeek();
                $this->endDate = $currentDate->copy()->endOfWeek();
                break;
            case 'month':
                $this->startDate = $currentDate->copy()->startOfMonth();
                $this->endDate = $currentDate->copy()->endOfMonth();
                break;
        }
        
        // Reload data when period changes
        $this->loadDashboardData();
        
        // Debug logging
        Log::info('Period changed', [
            'period' => $period,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'qualityRate' => $this->qualityRate ?? 'not_set'
        ]);
    }   

    public function loadDashboardData()
    {
        $this->loadBasicData();
        $this->loadOeeData();
        $this->loadTargetRealization();
        $this->loadPerformanceData();
    }

    protected function loadBasicData()
    {
        // Get production data for the selected period
        $this->todayProduction = Production::where('user_id', Auth::id())
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->sum('total_production');

        $this->todayDefects = Production::where('user_id', Auth::id())
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->sum('defect_count');

        // Calculate quality rate for the period
        if ($this->todayProduction > 0) {
            $goodProducts = $this->todayProduction - $this->todayDefects;
            $this->qualityRate = ($goodProducts / $this->todayProduction) * 100;
        } else {
            $this->qualityRate = 0;
        }

        // Debug logging
        Log::info('Basic data loaded', [
            'userId' => Auth::id(),
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'todayProduction' => $this->todayProduction,
            'todayDefects' => $this->todayDefects,
            'qualityRate' => $this->qualityRate
        ]);

        // Get active production
        $this->activeProduction = Production::where('user_id', Auth::id())
            ->whereIn('status', ['running', 'paused', 'problem'])
            ->first();

        // Calculate total downtime
        $this->totalDowntime = ProductionDowntime::whereHas('production', function($query) {
            $query->where('user_id', Auth::id());
        })
        ->whereBetween('start_time', [$this->startDate, $this->endDate])
        ->sum('duration_minutes');

        // Get recent downtimes
        $this->recentDowntimes = ProductionDowntime::whereHas('production', function($query) {
            $query->where('user_id', Auth::id());
        })
        ->latest()
        ->take(5)
        ->get();

        // Get recent problems
        $this->recentProblems = ProductionProblem::whereHas('production', function($query) {
            $query->where('user_id', Auth::id());
        })
        ->latest()
        ->take(5)
        ->get();
    }

    protected function loadOeeData()
    {
        $oeeRecords = OeeRecord::whereHas('production', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->get();

        if ($oeeRecords->isNotEmpty()) {
            $this->oeeData = (object) [
                'availability_rate' => $oeeRecords->avg('availability_rate'),
                'performance_rate' => $oeeRecords->avg('performance_rate'),
                'quality_rate' => $oeeRecords->avg('quality_rate'),
                'oee_score' => $oeeRecords->avg('oee_score')
            ];
        } else {
            $this->oeeData = null;
        }
    }

    protected function loadTargetRealization()
    {
        // Get latest production
        $production = Production::where('user_id', Auth::id())
            ->latest()
            ->first();

        if ($production) {
            // Set target based on period
            switch($this->selectedPeriod) {
                case 'today':
                    $this->productionTarget = $production->target_per_shift;
                    break;
                case 'week':
                    $this->productionTarget = $production->target_per_shift * 7;
                    break;
                case 'month':
                    $this->productionTarget = $production->target_per_shift * 30;
                    break;
            }

            // Debug: tampilkan range tanggal yang digunakan
            Log::info('Date Range:', [
                'start' => $this->startDate,
                'end' => $this->endDate,
                'user_id' => Auth::id(),
                'product' => $production->product
            ]);

            // Get total production for the period
            $query = Production::where('user_id', Auth::id())
                ->where('product', $production->product)
                ->whereBetween('created_at', [$this->startDate, $this->endDate]);
            
            // Debug: tampilkan query yang dijalankan
            Log::info('SQL:', ['query' => $query->toSql(), 'bindings' => $query->getBindings()]);

            $this->productionRealization = $query->sum('total_production');
        } else {
            $this->productionTarget = 0;
            $this->productionRealization = 0;
        }
    }

    protected function loadPerformanceData()
    {
        $this->performanceData = OeeRecord::whereHas('production', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->select('date', 'oee_score', 'availability_rate', 'performance_rate', 'quality_rate')
            ->orderBy('date', 'asc')
            ->get();
    }

    public function refreshDashboard()
    {
        $this->loadDashboardData();
        $this->dispatch('dashboardRefreshed');
    }

    public function render()
    {
        return view('livewire.karyawan.dashboard');
    }
}