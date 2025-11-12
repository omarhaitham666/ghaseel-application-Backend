<?php

namespace App\Services;

use App\Models\Service;
use App\Traits\HandlesMediaUploads;
use Illuminate\Http\UploadedFile;

class ServiceService
{
    use HandlesMediaUploads;

    /**
     * Create a new service.
     *
     * @param array $data
     * @param UploadedFile|null $image
     * @return Service
     */
    public function createService(array $data, ?UploadedFile $image = null): Service
    {
        // Remove image from data array (we'll handle it via media library)
        unset($data['image']);

        // Create the service
        $service = Service::create($data);

        // Store media if image provided
        if ($image) {
            $this->storeMedia($service, $image, 'images', false);
        }

        return $service->load('media');
    }

    /**
     * Update a service.
     *
     * @param Service $service
     * @param array $data
     * @param UploadedFile|null $image
     * @return Service
     */
    public function updateService(Service $service, array $data, ?UploadedFile $image = null): Service
    {
        // Remove image from data array (we'll handle it via media library)
        unset($data['image']);

        // Update service data
        $service->update($data);

        // Update media if image provided (deletes old, adds new)
        if ($image) {
            $this->updateMedia($service, $image, 'images');
        }

        return $service->fresh()->load('media');
    }

    /**
     * Delete a service.
     *
     * @param Service $service
     * @return bool
     */
    public function deleteService(Service $service): bool
    {
        // Delete media collection
        $this->deleteMedia($service, 'images');

        return $service->delete();
    }

    /**
     * Get all active services.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveServices()
    {
        return Service::where('is_active', true)
            ->with('media')
            ->get();
    }

    /**
     * Get all services (for admin).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllServices()
    {
        return Service::with('media')->get();
    }
}


