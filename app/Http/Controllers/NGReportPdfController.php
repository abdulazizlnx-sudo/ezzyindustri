<?php

namespace App\Http\Controllers;

use App\Models\NGReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class NGReportPdfController extends Controller
{
    public function generate(Request $request)
    {
        try {
            $filters = json_decode(base64_decode($request->query('filters')));
            
            Log::info('Starting NG Report PDF generation');
            
            $query = NGReport::query();
            
            // Apply filters if provided
            if ($filters) {
                if ($filters->startDate && $filters->endDate) {
                    $query->whereBetween('date', [$filters->startDate, $filters->endDate]);
                }
                if ($filters->selectedMachine) {
                    $query->where('machine_name', $filters->selectedMachine);
                }
                if ($filters->selectedShift) {
                    $query->where('shift', $filters->selectedShift);
                }
                if ($filters->selectedStatus) {
                    $query->where('status', $filters->selectedStatus);
                }
            }

            $reports = $query->orderBy('date', 'desc')->get();
            
            // Calculate summary
            $summary = [
                'total_ng' => $reports->sum('total_ng'),
                'avg_ng_percentage' => $reports->avg('ng_percentage'),
                'most_common_ng' => $reports->groupBy('ng_type')
                    ->map(function ($group) {
                        return $group->count();
                    })->sortDesc()->keys()->first()
            ];

            $pdf = PDF::loadView('pdf.ng-report', [
                'reports' => $reports,
                'summary' => $summary,
                'printedBy' => Auth::user()->name,
                'printDate' => now()->format('Y-m-d H:i:s')
            ]);

            // Ubah cara return PDF agar buka di tab baru
            return $pdf->stream('ng-report.pdf');

        } catch (\Exception $e) {
            Log::error('NG Report PDF generation failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to generate PDF'], 500);
        }
    }
}