<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserLocationRequest;
use App\Http\Requests\UpdateUserLocationRequest;
use App\Http\Resources\UserLocationResource;
use App\Http\Resources\UserLocationResourceCollection;
use App\Models\UserLocation;
use App\Services\UserLocationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserLocationController extends Controller
{
    protected UserLocationService $userLocationService;

    public function __construct(UserLocationService $userLocationService)
    {
        $this->userLocationService = $userLocationService;
    }

    /**
     * Get user's locations.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $locations = $this->userLocationService->getUserLocations();

            return (new UserLocationResourceCollection($locations))->additional([
                'status' => 'success',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء جلب العناوين: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created location.
     *
     * @param StoreUserLocationRequest $request
     * @return JsonResponse
     */
    public function store(StoreUserLocationRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $location = $this->userLocationService->createLocation($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'تم إنشاء العنوان بنجاح',
                'data' => new UserLocationResource($location),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء إنشاء العنوان: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified location.
     *
     * @param Request $request
     * @param UserLocation $location
     * @return JsonResponse
     */
    public function show(Request $request, UserLocation $location): JsonResponse
    {
        try {
            $location = $this->userLocationService->getLocationDetails($location);

            return response()->json([
                'status' => 'success',
                'data' => new UserLocationResource($location),
            ]);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() === 403 ? 403 : 500;
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $statusCode);
        }
    }

    /**
     * Update the specified location.
     *
     * @param UpdateUserLocationRequest $request
     * @param UserLocation $location
     * @return JsonResponse
     */
    public function update(UpdateUserLocationRequest $request, UserLocation $location): JsonResponse
    {
        try {
            $validated = $request->validated();
            $location = $this->userLocationService->updateLocation($location, $validated);

            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث العنوان بنجاح',
                'data' => new UserLocationResource($location),
            ]);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() === 403 ? 403 : 500;
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء تحديث العنوان: ' . $e->getMessage(),
            ], $statusCode);
        }
    }

    /**
     * Remove the specified location.
     *
     * @param UserLocation $location
     * @return JsonResponse
     */
    public function destroy(UserLocation $location): JsonResponse
    {
        try {
            $this->userLocationService->deleteLocation($location);

            return response()->json([
                'status' => 'success',
                'message' => 'تم حذف العنوان بنجاح',
            ]);
        } catch (\Exception $e) {
            $statusCode = $e->getCode() === 403 ? 403 : 500;
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء حذف العنوان: ' . $e->getMessage(),
            ], $statusCode);
        }
    }
}
