<?php

namespace App\Livewire\Manajerial\Quality;

use Livewire\Component;
use App\Models\NGReport;
use App\Models\FishboneAnalysis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QualityAnalysisDashboard extends Component
{
    public $ngDetails = [];
    public $summary;
    public $recentReports;
    public $recentAnalyses;
    public $chartData;

    public $dateRange = '30';
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->startDate = now()->subDays(30)->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
        $this->loadData();
    }

    public function updatedDateRange()
    {
        if ($this->dateRange !== 'custom') {
            $this->startDate = now()->subDays((int)$this->dateRange)->format('Y-m-d');
            $this->endDate = now()->format('Y-m-d');
            $this->loadData();
        }
    }

    public function loadData()
    {
        $startDate = $this->dateRange === 'custom' 
            ? $this->startDate 
            : now()->subDays((int)$this->dateRange);
            
        $endDate = $this->dateRange === 'custom'
            ? $this->endDate
            : now();

        // Update existing queries with new date range
        $this->summary = [
            'total_reports' => NGReport::whereBetween('date', [$startDate, $endDate])->count(),
            'avg_ng_rate' => NGReport::whereBetween('date', [$startDate, $endDate])->avg('ng_percentage'),
            'most_common_ng' => NGReport::whereBetween('date', [$startDate, $endDate])
                ->select('ng_type')
                ->groupBy('ng_type')
                ->orderByRaw('COUNT(*) DESC')
                ->first()?->ng_type ?? 'N/A',
            'total_analyses' => FishboneAnalysis::whereBetween('analysis_date', [$startDate, $endDate])->count()
        ];

        // Get recent reports
        $this->recentReports = NGReport::latest('date')
            ->take(5)
            ->get();

        // Get recent analyses
        $this->recentAnalyses = FishboneAnalysis::latest('analysis_date')
            ->take(5)
            ->get()
            ->map(function($analysis) {
                return [
                    'date' => $analysis->analysis_date,
                    'type' => 'Fishbone',
                    'title' => $analysis->title,
                    'url' => route('manajerial.quality.fishbone', ['id' => $analysis->id])
                ];
            });

        // Prepare and dispatch chart data immediately
        $ngTrend = NGReport::select(
            DB::raw('DATE(date) as date'),
            DB::raw('AVG(ng_percentage) as avg_ng_rate')
        )
        ->whereBetween('date', [now()->subDays(30), now()])
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    
        $ngDistribution = NGReport::select('ng_type', DB::raw('COUNT(*) as count'))
            ->whereBetween('date', [now()->subDays(30), now()])
            ->groupBy('ng_type')
            ->orderByDesc('count')
            ->get();
    
        // Dispatch langsung saat component di-mount
        $this->dispatch('chartDataUpdated', [
            'trend' => $ngTrend->toArray(),
            'distribution' => $ngDistribution->toArray()
        ]);
    }

    // Tambahkan method untuk refresh data
    public function refreshData()
    {
        $this->loadData();
    }

    #[On('loadNGDetails')]
    public function loadNGDetails($params)
    {
        Log::info('=== Start loadNGDetails ===');
        Log::info('Parameter yang diterima:', ['params' => $params]);
        
        try {
            $startDate = $this->dateRange === 'custom' 
                ? $this->startDate 
                : now()->subDays((int)$this->dateRange)->format('Y-m-d');
                
            $endDate = $this->dateRange === 'custom'
                ? $this->endDate
                : now()->format('Y-m-d');

            Log::info('Date Range:', [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'currentDateRange' => $this->dateRange
            ]);

            $query = NGReport::select(
                    'id', // Tambahkan id
                    'date',
                    'machine_name',
                    'total_ng as ng_count',
                    'ng_percentage',
                    'ng_type'
                )
                ->where('ng_type', $params['ngType'])
                ->whereDate('date', '>=', $startDate)
                ->whereDate('date', '<=', $endDate);

            Log::info('Query:', [
                'sql' => $query->toSql(),
                'bindings' => $query->getBindings()
            ]);

            $this->ngDetails = $query->get();

            Log::info('Query Result:', [
                'count' => $this->ngDetails->count(),
                'data' => $this->ngDetails->toArray()
            ]);

        } catch (\Exception $e) {
            Log::error('Error in loadNGDetails:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        
        Log::info('=== End loadNGDetails ===');
    }
}
