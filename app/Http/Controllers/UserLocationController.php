<?php

namespace App\Http\Controllers;

use App\Models\UserLocation;
use App\Services\UserLocationService;
use Illuminate\Http\Request;

class UserLocationController extends Controller
{
    public function __construct(private UserLocationService $service) {}

    public function index()
    {
        return response()->json($this->service->list());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'street_address' => 'required|string',
            'building_number' => 'nullable|string',
            'apartment' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'country' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'phone' => 'nullable|string',
        ]);

        return response()->json($this->service->create($data), 201);
    }

    public function update(Request $request, UserLocation $location)
    {
        $data = $request->all();
        return response()->json($this->service->update($location, $data));
    }

    public function destroy(UserLocation $location)
    {
        $this->service->delete($location);
        return response()->noContent();
    }
}
