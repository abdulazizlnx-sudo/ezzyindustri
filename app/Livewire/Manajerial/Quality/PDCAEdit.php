<?php

namespace App\Livewire\Manajerial\Quality;

use Livewire\Component;
use App\Models\PDCAProject;
use App\Models\PDCAAction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PDCAEdit extends Component
{
    public $projectId;
    public $project;
    public $title;
    public $description;
    public $startDate;
    public $targetDate;
    public $status;
    public $actions = [
        'plan' => [],
        'do' => [],
        'check' => [],
        'act' => []
    ];

    public function mount($id)
    {
        $this->projectId = $id;
        $this->project = PDCAProject::with('actions')->findOrFail($id);
        
        // Load project data
        $this->title = $this->project->title;
        $this->description = $this->project->description;
        $this->startDate = $this->project->start_date->format('Y-m-d');
        $this->targetDate = $this->project->target_date->format('Y-m-d');
        $this->status = $this->project->status;

        // Load actions
        foreach ($this->project->actions as $action) {
            $this->actions[$action->phase][] = [
                'id' => $action->id,
                'action' => $action->action,
                'detail' => $action->detail,
                'pic' => $action->pic,
                'target_date' => $action->target_date->format('Y-m-d'),
                'status' => $action->status
            ];
        }
    }

    public function addAction($phase)
    {
        $this->actions[$phase][] = [
            'action' => '',
            'detail' => '',
            'pic' => '',
            'target_date' => '',
            'status' => 'pending'
        ];
    }

    public function removeAction($phase, $index)
    {
        unset($this->actions[$phase][$index]);
        $this->actions[$phase] = array_values($this->actions[$phase]);
    }

    public function update()
    {
        $this->validate([
            'title' => 'required',
            'description' => 'required',
            'startDate' => 'required|date',
            'targetDate' => 'required|date|after:startDate',
            'status' => 'required'
        ]);

        try {
            DB::beginTransaction();

            // Update project
            $this->project->update([
                'title' => $this->title,
                'description' => $this->description,
                'start_date' => $this->startDate,
                'target_date' => $this->targetDate,
                'status' => $this->status
            ]);

            // Update actions
            foreach ($this->actions as $phase => $phaseActions) {
                foreach ($phaseActions as $action) {
                    if (isset($action['id'])) {
                        // Update existing action
                        PDCAAction::find($action['id'])->update([
                            'action' => $action['action'],
                            'detail' => $action['detail'],
                            'pic' => $action['pic'],
                            'target_date' => $action['target_date'],
                            'status' => $action['status']
                        ]);
                    } else {
                        // Create new action
                        PDCAAction::create([
                            'pdca_project_id' => $this->projectId,
                            'phase' => $phase,
                            'action' => $action['action'],
                            'detail' => $action['detail'],
                            'pic' => $action['pic'],
                            'target_date' => $action['target_date'],
                            'status' => $action['status']
                        ]);
                    }
                }
            }

            DB::commit();
            session()->flash('message', 'PDCA Project updated successfully.');
            return redirect()->route('pdca.detail', ['id' => $this->projectId]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update PDCA Project:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Failed to update PDCA Project: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.manajerial.quality.pdca-edit');
    }
}
