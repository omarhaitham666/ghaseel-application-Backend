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
    // الاسم: إذا العربي موجود بس، ننسخ الإنجليزي منه
    if (isset($data['name']['ar']) && empty($data['name']['en'])) {
        $data['name']['en'] = $data['name']['ar'];
    }
    if (isset($data['name']['en']) && empty($data['name']['ar'])) {
        $data['name']['ar'] = $data['name']['en'];
    }

    // نفس الشيء مع الوصف
    if (isset($data['description']['ar']) && empty($data['description']['en'])) {
        $data['description']['en'] = $data['description']['ar'];
    }
    if (isset($data['description']['en']) && empty($data['description']['ar'])) {
        $data['description']['ar'] = $data['description']['en'];
    }

    // إنشاء الخدمة
    $service = Service::create([
        'name' => $data['name'],               // JSON
        'description' => $data['description'], // JSON
        'is_active' => $data['is_active'] ?? true,
    ]);

    // رفع الصورة
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
        /** @var \App\Models\Service $service */
        // Update service data
        $service->update($data);

        // Update media if image provided (deletes old, adds new)
        /** @var \App\Models\Service $service */
        /** @var \Illuminate\Http\UploadedFile|null $image */
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
        /** @var \App\Models\Service $service */
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


