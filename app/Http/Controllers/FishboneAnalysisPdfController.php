<?php

namespace App\Http\Controllers;

use App\Models\FishboneAnalysis;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FishboneAnalysisPdfController extends Controller
{
    public function generate($id)
    {
        try {
            Log::info('Starting PDF generation');
            
            $analysis = FishboneAnalysis::with('causes')->find($id);
            
            // Optimize image loading
            $base64Image = null;
            if ($analysis->cloudinary_url) {
                try {
                    // Set timeout untuk file_get_contents
                    $ctx = stream_context_create([
                        'http' => [
                            'timeout' => 5 // 5 detik timeout
                        ]
                    ]);
                    $imageContent = file_get_contents($analysis->cloudinary_url, false, $ctx);
                    
                    // Compress image before encoding
                    if ($imageContent) {
                        $image = imagecreatefromstring($imageContent);
                        if ($image) {
                            ob_start();
                            imagejpeg($image, null, 60); // Compress to 60% quality
                            $imageContent = ob_get_clean();
                            imagedestroy($image);
                        }
                    }
                    $base64Image = base64_encode($imageContent);
                } catch (\Exception $e) {
                    Log::warning('Image optimization failed, using original image');
                    // Fallback to original image if optimization fails
                    $base64Image = base64_encode(file_get_contents($analysis->cloudinary_url));
                }
            }

            // Set memory limit temporarily for PDF generation
            ini_set('memory_limit', '256M');
            
            $pdf = PDF::loadView('pdf.fishbone-analysis', [
                'analysis' => $analysis,
                'causes' => $analysis->causes->groupBy('category'),
                'base64Image' => $base64Image,
                'printedBy' => Auth::user()->name
            ]);

            $pdf->setPaper('A4', 'landscape');
            
            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="fishbone-analysis-' . $id . '.pdf"'
            ]);

        } catch (\Exception $e) {
            Log::error('PDF generation failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to generate PDF');
        }
    }
}