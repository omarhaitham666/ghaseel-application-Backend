<?php
namespace App\Services;

use App\Models\UserLocation;
use Illuminate\Support\Facades\Auth;

class UserLocationService
{
    public function list()
    {
        return UserLocation::where('user_id', Auth::id())->get();
    }

    public function create(array $data)
    {
        $data['user_id'] = Auth::id();
        return UserLocation::create($data);
    }

    public function update(UserLocation $location, array $data)
    {
        $this->authorize($location);
        $location->update($data);
        return $location;
    }

    public function delete(UserLocation $location)
    {
        $this->authorize($location);
        $location->delete();
    }

    private function authorize(UserLocation $location)
    {
        if ($location->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    }
}
