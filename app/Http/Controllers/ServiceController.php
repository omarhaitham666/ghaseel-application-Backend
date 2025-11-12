<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use App\Services\ServiceService;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $services = $this->serviceService->getActiveServices();

        return response()->json([
            'status' => 'success',
            'data' => $services,
        ]);
    }

    /**
     * Display all services (for admin).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $services = $this->serviceService->getAllServices();

        return response()->json([
            'status' => 'success',
            'data' => $services,
        ]);
    }

    /**
     * Store a newly created service.
     *
     * @param StoreServiceRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreServiceRequest $request)
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
                'data' => $service,
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
     * @param Service $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Service $service)
    {
        $service->load('media');
        
        return response()->json([
            'status' => 'success',
            'data' => $service,
        ]);
    }

    /**
     * Update the specified service.
     *
     * @param UpdateServiceRequest $request
     * @param Service $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateServiceRequest $request, Service $service)
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
                'data' => $service,
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Service $service)
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
