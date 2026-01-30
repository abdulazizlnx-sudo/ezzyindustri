<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    protected $cloudinary;

    public function __construct()
    {
        $cloudName = config('services.cloudinary.cloud_name');
        $apiKey = config('services.cloudinary.api_key');
        $apiSecret = config('services.cloudinary.api_secret');

        // Check if Cloudinary is configured
        if (empty($cloudName) || empty($apiKey) || empty($apiSecret) || 
            $cloudName === 'your_cloud_name' || 
            $apiKey === 'your_api_key' || 
            $apiSecret === 'your_api_secret') {
            
            Log::warning('Cloudinary is not configured. Using fallback storage.');
            $this->cloudinary = null;
            return;
        }

        $this->cloudinary = new Cloudinary([
            'cloud_name' => $cloudName,
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
            'secure' => true
        ]);
    }

    public function upload($file, $options = [])
    {
        // If Cloudinary is not configured, use local storage
        if (!$this->cloudinary) {
            return $this->uploadToLocal($file, $options);
        }

        try {
            if (!$file->isValid()) {
                throw new \Exception('Invalid file upload');
            }

            // Set default options and merge with provided options
            $uploadOptions = array_merge([
                'folder' => 'sop_images',
                'resource_type' => 'auto'
            ], $options);

            Log::info('Uploading to Cloudinary', [
                'folder' => $uploadOptions['folder'],
                'filename' => $file->getClientOriginalName()
            ]);

            $result = $this->cloudinary->uploadApi()->upload($file->getPathname(), $uploadOptions);

            if (!isset($result['secure_url'])) {
                throw new \Exception('Upload failed - No URL returned');
            }

            return [
                'success' => true,
                'url' => $result['secure_url'],
                'public_id' => $result['public_id'],
                'version' => $result['version'] ?? null,
                'url_with_version' => $this->getUrlWithVersion($result['public_id'], $result['version'] ?? null)
            ];

        } catch (\Exception $e) {
            Log::error('Cloudinary upload error: ' . $e->getMessage());
            Log::error('File details: ' . json_encode([
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType()
            ]));
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function getUrl($publicId, $version = null)
    {
        if (!$this->cloudinary) {
            return null;
        }

        $cloudName = config('services.cloudinary.cloud_name');
        $url = "https://res.cloudinary.com/{$cloudName}/image/upload/";
        
        if ($version) {
            $url .= "v{$version}/";
        }
        
        $url .= $publicId;
        
        return $url;
    }

    public function getUrlWithVersion($publicId, $version = null)
    {
        if (!$this->cloudinary) {
            return null;
        }

        $cloudName = config('services.cloudinary.cloud_name');
        $url = "https://res.cloudinary.com/{$cloudName}/image/upload/";
        
        if ($version) {
            $url .= "v{$version}/";
        }
        
        $url .= $publicId;
        
        return $url;
    }

    public function delete($publicId)
    {
        // If Cloudinary is not configured, skip deletion
        if (!$this->cloudinary) {
            Log::info('Cloudinary not configured, skipping deletion of: ' . $publicId);
            return true;
        }

        try {
            $this->cloudinary->uploadApi()->destroy($publicId);
            return true;
        } catch (\Exception $e) {
            Log::error('Cloudinary delete error: ' . $e->getMessage());
            return false;
        }
    }

    private function uploadToLocal($file, $options = [])
    {
        try {
            if (!$file->isValid()) {
                throw new \Exception('Invalid file upload');
            }

            $folder = $options['folder'] ?? 'sop_images';
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs($folder, $filename, 'public');

            Log::info('File uploaded to local storage', [
                'path' => $path,
                'filename' => $filename
            ]);

            return [
                'success' => true,
                'url' => Storage::url($path),
                'public_id' => $path
            ];

        } catch (\Exception $e) {
            Log::error('Local upload error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}