<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FishboneCause extends Model
{
    protected $fillable = [
        'fishbone_analysis_id',
        'category',
        'cause',
        'description',
        'status',
        'pic',
        'target_date'
    ];

    protected $casts = [
        'target_date' => 'date'
    ];

    public function fishboneAnalysis()
    {
        return $this->belongsTo(FishboneAnalysis::class);
    }
}