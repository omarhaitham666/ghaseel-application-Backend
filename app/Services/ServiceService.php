<?php

namespace App\Services;

use App\Models\Service;
use App\Traits\HandlesMediaUploads;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        // Handle name: if Arabic exists but English is empty, copy Arabic to English
        if (isset($data['name']['ar']) && empty($data['name']['en'])) {
            $data['name']['en'] = $data['name']['ar'];
        }
        if (isset($data['name']['en']) && empty($data['name']['ar'])) {
            $data['name']['ar'] = $data['name']['en'];
        }

        // Handle description: if Arabic exists but English is empty, copy Arabic to English
        if (isset($data['description']['ar']) && empty($data['description']['en'])) {
            $data['description']['en'] = $data['description']['ar'];
        }
        if (isset($data['description']['en']) && empty($data['description']['ar'])) {
            $data['description']['ar'] = $data['description']['en'];
        }

        // Remove image from data array (we'll handle it via media library)
        unset($data['image']);

        // Create service within a transaction
        return DB::transaction(function () use ($data, $image) {
            $service = Service::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'is_active' => $data['is_active'] ?? true,
            ]);

            // Upload image if provided
            if ($image && $image->isValid()) {
                try {
                    $this->storeMedia($service, $image, 'images', false);
                } catch (\Exception $e) {
                    Log::error('Failed to upload service image: ' . $e->getMessage());
                    // Continue without image if upload fails
                }
            }

            return $service->load('media');
        });
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

        // Update service within a transaction
        return DB::transaction(function () use ($service, $data, $image) {
            // Update service data
            $service->update($data);

            // Update media if image provided (deletes old, adds new)
            if ($image && $image->isValid()) {
                try {
                    $this->updateMedia($service, $image, 'images');
                } catch (\Exception $e) {
                    Log::error('Failed to update service image: ' . $e->getMessage());
                    // Continue without updating image if upload fails
                }
            }

            return $service->fresh()->load('media');
        });
    }

    /**
     * Delete a service.
     *
     * @param Service $service
     * @return bool
     */
    public function deleteService(Service $service): bool
    {
        return DB::transaction(function () use ($service) {
            // Delete media collection
            try {
                $this->deleteMedia($service, 'images');
            } catch (\Exception $e) {
                Log::error('Failed to delete service media: ' . $e->getMessage());
                // Continue with deletion even if media deletion fails
            }

            return $service->delete();
        });
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
            ->latest()
            ->get();
    }

    /**
     * Get all services (for admin).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllServices()
    {
        return Service::with('media')
            ->latest()
            ->get();
    }

    /**
     * Get service details with media.
     *
     * @param Service $service
     * @return Service
     */
    public function getServiceDetails(Service $service): Service
    {
        return $service->load('media');
    }
}
