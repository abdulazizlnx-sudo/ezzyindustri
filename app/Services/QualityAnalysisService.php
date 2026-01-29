<?php

namespace App\Services;

use App\Models\NGReport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QualityAnalysisService
{
    public function getParetoData($startDate = null, $endDate = null)
    {
        $query = NGReport::query();
        
        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        $defectData = $query->select('ng_type', DB::raw('SUM(total_ng) as count'))
                           ->whereNotNull('ng_type')
                           ->where('ng_type', '!=', '')
                           ->groupBy('ng_type')
                           ->orderBy('count', 'desc')
                           ->get();
    
        // Debug data
        Log::info('SQL Query:', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings(),
            'results' => $defectData->toArray()
        ]);
    
        $total = $defectData->sum('count');
        $cumulative = 0;
        
        $result = $defectData->map(function ($item) use ($total, &$cumulative) {
            $percentage = ($item->count / $total) * 100;
            $cumulative += $percentage;
            
            return [
                'defect' => $item->ng_type,
                'count' => (int)$item->count, // Pastikan count adalah integer
                'percentage' => round($percentage, 2),
                'cumulative' => round($cumulative, 2)
            ];
        });
    
        // Debug final result
        Log::info('Final Data:', $result->toArray());
    
        return $result;
    }
}
