<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDCAAction extends Model
{
    protected $table = 'pdca_actions';
    
    protected $fillable = [
        'pdca_project_id',
        'phase',
        'action',
        'detail',
        'pic',
        'target_date',
        'actual_date',
        'status',
        'result'
    ];

    protected $casts = [
        'target_date' => 'date',
        'actual_date' => 'date'
    ];

    // Phase constants
    const PHASE_PLAN = 'plan';
    const PHASE_DO = 'do';
    const PHASE_CHECK = 'check';
    const PHASE_ACT = 'act';

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';

    public function project()
    {
        return $this->belongsTo(PDCAProject::class, 'pdca_project_id');
    }
}