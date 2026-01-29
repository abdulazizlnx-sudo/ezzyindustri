<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PDCAProject extends Model
{
    protected $table = 'pdca_projects';
    
    protected $fillable = [
        'fishbone_analysis_id',
        'title',
        'description',
        'start_date',
        'target_date',
        'status',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'target_date' => 'date'
    ];

    // Status yang tersedia
    const STATUS_DRAFT = 'draft';
    const STATUS_PLANNING = 'planning';
    const STATUS_IMPLEMENTATION = 'implementation';
    const STATUS_EVALUATION = 'evaluation';
    const STATUS_COMPLETED = 'completed';
    const STATUS_ON_HOLD = 'on_hold';

    public function actions()
    {
        return $this->hasMany(PDCAAction::class, 'pdca_project_id');
    }

    public function fishboneAnalysis()
    {
        return $this->belongsTo(FishboneAnalysis::class);
    }
}