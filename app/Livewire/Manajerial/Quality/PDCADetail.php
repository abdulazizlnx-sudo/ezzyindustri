<?php

namespace App\Livewire\Manajerial\Quality;

use Livewire\Component;
use App\Models\PDCAProject;
use App\Models\PDCAAction;

class PDCADetail extends Component
{
    public $projectId;
    public $project;

    public function mount($id)
    {
        $this->projectId = $id;
        $this->project = PDCAProject::with('actions')->findOrFail($id);
    }

    public function updateActionStatus($actionId)
    {
        $action = PDCAAction::find($actionId);
        
        // Rotate through statuses: pending -> in_progress -> completed
        switch($action->status) {
            case 'pending':
                $action->status = 'in_progress';
                break;
            case 'in_progress':
                $action->status = 'completed';
                break;
            case 'completed':
                $action->status = 'pending';
                break;
        }
        
        $action->save();
        $this->project = $this->project->fresh(['actions']);
    }

    public function back()
    {
        return redirect()->route('pdca.index');
    }

    public function render()
    {
        return view('livewire.manajerial.quality.pdca-detail');
    }
}
