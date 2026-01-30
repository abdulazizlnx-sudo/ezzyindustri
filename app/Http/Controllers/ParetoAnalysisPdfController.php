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
        // Get parameters from URL query string first, then fallback to session
        $startDate = $request->get('start_date', session('pareto_export_params.startDate', now()->subMonth()->format('Y-m-d')));
        $endDate = $request->get('end_date', session('pareto_export_params.endDate', now()->format('Y-m-d')));
        $machine = $request->get('machine', session('pareto_export_params.machine', ''));
        $product = $request->get('product', session('pareto_export_params.product', ''));
        $shift = $request->get('shift', session('pareto_export_params.shift', ''));

        // Get fresh data based on parameters
        $service = new QualityAnalysisService();
        $paretoData = $service->getParetoData(
            $startDate,
            $endDate,
            $machine,
            $product,
            $shift
        );

        $pdf = PDF::loadView('pdf.pareto-analysis', [
            'paretoData' => $paretoData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'machine' => $machine ?: 'All',
            'product' => $product ?: 'All',
            'shift' => $shift ?: 'All',
            'printedBy' => Auth::user()->name
        ]);

        session()->forget('pareto_export_params');

        return $pdf->stream('pareto-analysis.pdf');
    }
}