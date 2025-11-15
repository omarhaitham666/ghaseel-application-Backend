<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\HasMedia;

trait HandlesMediaUploads
{
    /**
     * Store media from uploaded file.
     *
     * @param HasMedia $model The model that has media
     * @param UploadedFile|null $file The uploaded file
     * @param string $collection The media collection name (default: 'images')
     * @param bool $deleteExisting Whether to delete existing media (default: true)
     * @return void
     */
    public function storeMedia(
        HasMedia $model,
        ?UploadedFile $file,
        string $collection = 'images',
        bool $deleteExisting = true
    ): void {
        // Validate file
        if (!$file || !$file->isValid() || $file->getSize() <= 0) {
            return;
        }

        // Delete existing media if requested
        if ($deleteExisting && $model->hasMedia($collection)) {
            $model->clearMediaCollection($collection);
        }

        // Add media using Spatie Media Library
        // Use getPathname() which returns the temporary file path
        // This is the most reliable method for UploadedFile instances
        try {
            $filePath = $file->getPathname();
            
            // Ensure file exists and is readable
            if (!file_exists($filePath) || !is_readable($filePath)) {
                \Log::warning('Media file not accessible', [
                    'path' => $filePath,
                    'model' => get_class($model),
                    'collection' => $collection
                ]);
                return;
            }

            $model->addMedia($filePath)
                ->usingName($file->getClientOriginalName())
                ->usingFileName($this->generateUniqueFileName($file))
                ->toMediaCollection($collection);
        } catch (\Exception $e) {
            \Log::error('Failed to store media', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
                'model' => get_class($model),
                'collection' => $collection
            ]);
            throw $e;
        }
    }

    /**
     * Update media from uploaded file (deletes old, adds new).
     *
     * @param HasMedia $model The model that has media
     * @param UploadedFile|null $file The uploaded file
     * @param string $collection The media collection name (default: 'images')
     * @return void
     */
    public function updateMedia(
        HasMedia $model,
        ?UploadedFile $file,
        string $collection = 'images'
    ): void {
        $this->storeMedia($model, $file, $collection, true);
    }

    /**
     * Delete media from collection.
     *
     * @param HasMedia $model The model that has media
     * @param string $collection The media collection name (default: 'images')
     * @return void
     */
    public function deleteMedia(HasMedia $model, string $collection = 'images'): void
    {
        if ($model->hasMedia($collection)) {
            $model->clearMediaCollection($collection);
        }
    }

    /**
     * Generate a unique file name to avoid conflicts.
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateUniqueFileName(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        
        // Generate unique filename: original_name_timestamp.extension
        return $name . '_' . time() . '_' . uniqid() . '.' . $extension;
    }
}
