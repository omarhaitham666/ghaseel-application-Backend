<?php

namespace App\Services;

use App\Models\UserLocation;
use Illuminate\Support\Facades\Auth;

class UserLocationService
{
    /**
     * Get user's locations.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserLocations()
    {
        return UserLocation::where('user_id', Auth::id())->latest()->get();
    }

    /**
     * Create a new user location.
     *
     * @param array $data
     * @return UserLocation
     */
    public function createLocation(array $data): UserLocation
    {
        $data['user_id'] = Auth::id();
        return UserLocation::create($data);
    }

    /**
     * Update user location.
     *
     * @param UserLocation $location
     * @param array $data
     * @return UserLocation
     */
    public function updateLocation(UserLocation $location, array $data): UserLocation
    {
        $this->authorize($location);
        $location->update($data);
        return $location->fresh();
    }

    /**
     * Delete user location.
     *
     * @param UserLocation $location
     * @return bool
     */
    public function deleteLocation(UserLocation $location): bool
    {
        $this->authorize($location);
        return $location->delete();
    }

    /**
     * Get location details.
     *
     * @param UserLocation $location
     * @return UserLocation
     */
    public function getLocationDetails(UserLocation $location): UserLocation
    {
        $this->authorize($location);
        return $location;
    }

    /**
     * Authorize access to location.
     *
     * @param UserLocation $location
     * @return void
     */
    private function authorize(UserLocation $location): void
    {
        if ($location->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول إلى هذا العنوان');
        }
    }
}
