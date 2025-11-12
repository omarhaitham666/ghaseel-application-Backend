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

        // Add media using the file's real path
        $realPath = $file->getRealPath();
        if ($realPath && file_exists($realPath) && is_readable($realPath)) {
            $model->addMedia($realPath)
                ->usingName($file->getClientOriginalName())
                ->usingFileName($file->getClientOriginalName())
                ->toMediaCollection($collection);
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
}

