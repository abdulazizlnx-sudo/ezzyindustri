<?php

namespace App\Livewire\Manajerial\Quality;

use Livewire\Component;
use App\Models\PDCAProject;
use App\Models\PDCAAction;
use App\Models\FishboneAnalysis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PDCAManagement extends Component
{
    public $fishboneAnalyses;
    public $selectedFishbone;
    public $title;
    public $description;
    public $startDate;
    public $targetDate;
    public $status = 'draft';
    
    public $actions = [
        'plan' => [],
        'do' => [],
        'check' => [],
        'act' => []
    ];

    protected $rules = [
        'selectedFishbone' => 'required',
        'title' => 'required|string|max:255',
        'description' => 'required',
        'startDate' => 'required|date',
        'targetDate' => 'required|date|after:startDate',
        'status' => 'required|in:draft,planning,implementation,evaluation,completed,on_hold',
    ];

    public function mount()
    {
        $this->fishboneAnalyses = FishboneAnalysis::all();
    }

    public function addAction($phase)
    {
        $this->actions[$phase][] = [
            'action' => '',
            'detail' => '',
            'pic' => '',
            'target_date' => '',
            'actual_date' => null,
            'status' => PDCAAction::STATUS_PENDING,
            'result' => ''
        ];
    }

    public function removeAction($phase, $index)
    {
        unset($this->actions[$phase][$index]);
        $this->actions[$phase] = array_values($this->actions[$phase]);
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $project = PDCAProject::create([
                'fishbone_analysis_id' => $this->selectedFishbone,
                'title' => $this->title,
                'description' => $this->description,
                'start_date' => $this->startDate,
                'target_date' => $this->targetDate,
                'status' => $this->status,
                'created_by' => Auth::user()->name
            ]);

            foreach ($this->actions as $phase => $phaseActions) {
                foreach ($phaseActions as $action) {
                    if (!empty($action['action'])) {
                        PDCAAction::create([
                            'pdca_project_id' => $project->id,
                            'phase' => $phase,
                            'action' => $action['action'],
                            'detail' => $action['detail'],
                            'pic' => $action['pic'],
                            'target_date' => $action['target_date'],
                            'actual_date' => $action['actual_date'] ?? null,
                            'status' => $action['status'],
                            'result' => $action['result'] ?? null
                        ]);
                    }
                }
            }

            DB::commit();
            session()->flash('message', 'PDCA Project created successfully.');
            $this->resetForm();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create PDCA Project:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Failed to create PDCA Project: ' . $e->getMessage());
        }
    }

    private function resetForm()
    {
        $this->selectedFishbone = null;
        $this->title = '';
        $this->description = '';
        $this->startDate = '';
        $this->targetDate = '';
        $this->status = 'draft';
        $this->actions = [
            'plan' => [],
            'do' => [],
            'check' => [],
            'act' => []
        ];
    }

    public function render()
    {
        $pdcaProjects = PDCAProject::with(['actions', 'fishboneAnalysis'])->latest()->get();
        
        return view('livewire.manajerial.quality.pdca-management', [
            'pdcaProjects' => $pdcaProjects
        ]);
    }

    public function viewProject($id)
    {
        return redirect()->route('pdca.detail', ['id' => $id]);
    }
}
