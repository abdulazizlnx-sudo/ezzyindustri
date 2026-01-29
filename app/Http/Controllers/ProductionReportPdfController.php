<?php

namespace App\Http\Controllers;

use App\Models\Production;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ProductionReportPdfController extends Controller
{
    public function generate(Request $request)
    {
        $productions = Production::with(['machine', 'shift', 'user', 'oeeRecord'])
            ->where('status', 'finished')
            ->whereBetween('start_time', [$request->dateFrom, $request->dateTo])
            ->when($request->machineId, fn($q) => $q->where('machine_id', $request->machineId))
            ->when($request->operatorId, fn($q) => $q->where('user_id', $request->operatorId))
            ->when($request->shiftId, fn($q) => $q->where('shift_id', $request->shiftId))
            ->get();
        
        $pdf = Pdf::loadView('pdf.production-report', [
            'productions' => $productions,
            'dateFrom' => $request->dateFrom,
            'dateTo' => $request->dateTo,
            'printedBy' => Auth::user()->name
        ]);
        
        return $pdf->stream('laporan-produksi.pdf');
    }
}