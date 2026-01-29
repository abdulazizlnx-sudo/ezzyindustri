<?php

namespace App\Livewire\Manajerial\Quality;

use Livewire\Component;
use App\Models\NGReport as NGReportModel;
use Illuminate\Support\Facades\DB;
use App\Exports\NGReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;


class NGReport extends Component
{
    // Hapus WithPagination
    public $startDate;
    public $endDate;
    public $selectedMachine;
    public $selectedShift;
    public $selectedStatus;
    public $search;
    public $selectedReport;
    public $showDetailModal = false;

    public function mount()
    {
        $this->startDate = now()->subDays(30)->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function render()
    {
        // Base query
        $query = NGReportModel::query();
    
        // Filter by date range
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('date', [$this->startDate, $this->endDate]);
        }
    
        // Filter by machine
        if ($this->selectedMachine) {
            $query->where('machine_name', $this->selectedMachine);
        }
    
        // Filter by shift
        if ($this->selectedShift) {
            $query->where('shift', $this->selectedShift);
        }
    
        // Filter by status
        if ($this->selectedStatus) {
            $query->where('status', $this->selectedStatus);
        }
    
        // Search functionality
        if ($this->search) {
            $query->where(function($q) {
                $q->where('operator_name', 'like', '%' . $this->search . '%')
                  ->orWhere('product_name', 'like', '%' . $this->search . '%')
                  ->orWhere('ng_type', 'like', '%' . $this->search . '%');
            });
        }
    
        // Get filtered data
        $reports = $query->orderBy('date', 'desc')->get();
    
        // Get machines for dropdown (unfiltered)
        $machines = NGReportModel::select('machine_name')
            ->distinct()
            ->orderBy('machine_name')
            ->pluck('machine_name');
    
        // Calculate summary based on filtered data
        $summary = [
            'total_ng' => $query->sum('total_ng'),
            'avg_ng_percentage' => $query->avg('ng_percentage'),
            'most_common_ng' => DB::table('ng_reports')
                ->select('ng_type', DB::raw('COUNT(*) as count'))
                ->whereBetween('date', [$this->startDate, $this->endDate])
                ->when($this->selectedMachine, function($q) {
                    return $q->where('machine_name', $this->selectedMachine);
                })
                ->when($this->selectedShift, function($q) {
                    return $q->where('shift', $this->selectedShift);
                })
                ->when($this->selectedStatus, function($q) {
                    return $q->where('status', $this->selectedStatus);
                })
                ->groupBy('ng_type')
                ->orderByRaw('COUNT(*) DESC')
                ->first()
        ];
    
        return view('livewire.manajerial.quality.ng-report', [
            'reports' => $reports,
            'machines' => $machines,
            'summary' => $summary
        ]);
    }

    // Hapus method updatingSearch karena tidak perlu lagi
    
    public function exportExcel()
    {
        $query = NGReportModel::query();
        
        // Apply filters
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('date', [$this->startDate, $this->endDate]);
        }
        if ($this->selectedMachine) {
            $query->where('machine_name', $this->selectedMachine);
        }
        if ($this->selectedShift) {
            $query->where('shift', $this->selectedShift);
        }
        if ($this->selectedStatus) {
            $query->where('status', $this->selectedStatus);
        }
    
        $reports = $query->orderBy('date', 'desc')->get();
        
        $summary = [
            'total_ng' => $reports->sum('total_ng'),
            'avg_ng_percentage' => $reports->avg('ng_percentage')
        ];
    
        return Excel::download(new NGReportExport(
            $reports,
            $this->startDate,
            $this->endDate,
            $this->selectedMachine,
            $this->selectedShift,
            $this->selectedStatus,
            $summary,
            Auth::user()->name
        ), 'ng-report.xlsx');
    }

    public function exportPdf()
    {
        $filters = [
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'selectedMachine' => $this->selectedMachine,
            'selectedShift' => $this->selectedShift,
            'selectedStatus' => $this->selectedStatus
        ];
    
        $url = route('ng-report.pdf', ['filters' => base64_encode(json_encode($filters))]);
        
        $this->dispatch('openPdfInNewTab', url: $url);
    }

    public function viewDetail($id)
    {
        $this->selectedReport = NGReportModel::find($id);
        $this->showDetailModal = true;
        $this->dispatch('show-modal');
    }

    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->selectedReport = null;
        $this->dispatch('hide-modal');
    }
}