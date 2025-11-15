<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\ServiceResourceCollection;
use App\Models\Service;
use App\Services\ServiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected ServiceService $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    /**
     * Display a listing of active services (for users).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $services = $this->serviceService->getActiveServices();

            return (new ServiceResourceCollection($services))->additional([
                'status' => 'success',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء جلب الخدمات: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display all services (for admin).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request): JsonResponse
    {
        try {
            $services = $this->serviceService->getAllServices();

            return (new ServiceResourceCollection($services))->additional([
                'status' => 'success',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء جلب الخدمات: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created service.
     *
     * @param StoreServiceRequest $request
     * @return JsonResponse
     */
    public function store(StoreServiceRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $image = $request->hasFile('image') && $request->file('image')->isValid() 
                ? $request->file('image') 
                : null;

            $service = $this->serviceService->createService($data, $image);

            return response()->json([
                'status' => 'success',
                'message' => 'تم إنشاء الخدمة بنجاح',
                'data' => new ServiceResource($service),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء إنشاء الخدمة: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified service.
     *
     * @param Request $request
     * @param Service $service
     * @return JsonResponse
     */
    public function show(Request $request, Service $service): JsonResponse
    {
        try {
            $service = $this->serviceService->getServiceDetails($service);

            return response()->json([
                'status' => 'success',
                'data' => new ServiceResource($service),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء جلب تفاصيل الخدمة: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified service.
     *
     * @param UpdateServiceRequest $request
     * @param Service $service
     * @return JsonResponse
     */
    public function update(UpdateServiceRequest $request, Service $service): JsonResponse
    {
        try {
            $data = $request->validated();
            $image = $request->hasFile('image') && $request->file('image')->isValid() 
                ? $request->file('image') 
                : null;

            $service = $this->serviceService->updateService($service, $data, $image);

            return response()->json([
                'status' => 'success',
                'message' => 'تم تحديث الخدمة بنجاح',
                'data' => new ServiceResource($service),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء تحديث الخدمة: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified service.
     *
     * @param Service $service
     * @return JsonResponse
     */
    public function destroy(Service $service): JsonResponse
    {
        try {
            $this->serviceService->deleteService($service);

            return response()->json([
                'status' => 'success',
                'message' => 'تم حذف الخدمة بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء حذف الخدمة: ' . $e->getMessage(),
            ], 500);
        }
    }
}
