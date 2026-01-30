<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SopStep;
use App\Models\ProductionProblem;
use App\Models\FishboneAnalysis;
use Illuminate\Support\Facades\Log;

class FixCloudinaryUrls extends Command
{
    protected $signature = 'cloudinary:fix-urls';
    protected $description = 'Fix Cloudinary URLs to include version numbers';

    public function handle()
    {
        $this->info('Fixing Cloudinary URLs...');
        
        $cloudName = config('services.cloudinary.cloud_name');
        
        // Fix SopStep images
        $sopSteps = SopStep::whereNotNull('cloudinary_id')
            ->where('cloudinary_id', 'like', 'sop_images/%')
            ->get();
            
        foreach ($sopSteps as $step) {
            $currentUrl = $step->gambar_path;
            $cloudinaryId = $step->cloudinary_id;
            
            // Extract version from current URL if exists
            if (preg_match('/v(\d+)\//', $currentUrl, $matches)) {
                $version = $matches[1];
                $correctUrl = "https://res.cloudinary.com/{$cloudName}/image/upload/v{$version}/{$cloudinaryId}.jpg";
                
                if ($currentUrl !== $correctUrl) {
                    $step->gambar_path = $correctUrl;
                    $step->save();
                    
                    $this->info("Fixed SopStep ID {$step->id}: {$correctUrl}");
                }
            }
        }
        
        // Fix ProductionProblem images
        $problems = ProductionProblem::whereNotNull('cloudinary_id')
            ->where('cloudinary_id', 'not like', '')
            ->get();
            
        foreach ($problems as $problem) {
            $currentUrl = $problem->cloudinary_url;
            $cloudinaryId = $problem->cloudinary_id;
            
            if ($currentUrl && $cloudinaryId) {
                // Extract version from current URL if exists
                if (preg_match('/v(\d+)\//', $currentUrl, $matches)) {
                    $version = $matches[1];
                    $correctUrl = "https://res.cloudinary.com/{$cloudName}/image/upload/v{$version}/{$cloudinaryId}";
                    
                    if ($currentUrl !== $correctUrl) {
                        $problem->cloudinary_url = $correctUrl;
                        $problem->save();
                        
                        $this->info("Fixed ProductionProblem ID {$problem->id}: {$correctUrl}");
                    }
                }
            }
        }
        
        // Fix FishboneAnalysis images
        $analyses = FishboneAnalysis::whereNotNull('cloudinary_id')
            ->where('cloudinary_id', 'not like', '')
            ->get();
            
        foreach ($analyses as $analysis) {
            $currentUrl = $analysis->cloudinary_url;
            $cloudinaryId = $analysis->cloudinary_id;
            
            if ($currentUrl && $cloudinaryId) {
                // Extract version from current URL if exists
                if (preg_match('/v(\d+)\//', $currentUrl, $matches)) {
                    $version = $matches[1];
                    $correctUrl = "https://res.cloudinary.com/{$cloudName}/image/upload/v{$version}/{$cloudinaryId}";
                    
                    if ($currentUrl !== $correctUrl) {
                        $analysis->cloudinary_url = $correctUrl;
                        $analysis->save();
                        
                        $this->info("Fixed FishboneAnalysis ID {$analysis->id}: {$correctUrl}");
                    }
                }
            }
        }
        
        $this->info('Cloudinary URLs fixed successfully!');
        
        return Command::SUCCESS;
    }
}
