<?php

namespace App\Services;

class ChartService
{
    public function generateParetoChart($data)
    {
        // Generate a simple placeholder image for now
        $width = 800;
        $height = 400;
        $image = imagecreatetruecolor($width, $height);
        
        // Add white background
        $white = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $white);
        
        // Capture the image
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        
        // Clean up
        imagedestroy($image);
        
        // Return base64 encoded image
        return 'data:image/png;base64,' . base64_encode($imageData);
    }
}