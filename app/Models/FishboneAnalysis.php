<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FishboneAnalysis extends Model
{
    protected $fillable = [
        'ng_report_id',
        'quality_check_id',
        'title',
        'problem_statement',
        'analysis_date',
        'created_by',
        'status',
        'cloudinary_url',  // tambahkan ini
        'cloudinary_id'    // dan ini
    ];

    protected $casts = [
        'analysis_date' => 'date'
    ];

    public function ngReport()
    {
        return $this->belongsTo(NGReport::class);
    }

    public function qualityCheck()
    {
        return $this->belongsTo(QualityCheck::class);
    }

    public function causes()
    {
        return $this->hasMany(FishboneCause::class);
    }
}