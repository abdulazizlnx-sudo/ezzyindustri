<?php

namespace App\Http\Controllers;

use App\Models\NGReport;
use App\Services\QualityAnalysisService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParetoAnalysisPdfController extends Controller
{
    public function generate(Request $request)
    {
        $params = session('pareto_export_params', []);
        
        $pdf = PDF::loadView('pdf.pareto-analysis', [
            'paretoData' => $params['paretoData'] ?? [],
            'startDate' => $params['startDate'] ?? now()->subMonth()->format('Y-m-d'),
            'endDate' => $params['endDate'] ?? now()->format('Y-m-d'),
            'machine' => $params['machine'] ?? 'All',
            'product' => $params['product'] ?? 'All',
            'shift' => $params['shift'] ?? 'All',
            'printedBy' => Auth::user()->name
        ]);
        
        session()->forget('pareto_export_params');
        
        return $pdf->stream('pareto-analysis.pdf');
    }
}