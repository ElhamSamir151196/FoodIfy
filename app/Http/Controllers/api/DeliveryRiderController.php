<?php

namespace App\Http\Controllers\Api;

use App\Actions\DeliveryRider\CreateDeliveryRiderAction;
use App\Actions\DeliveryRider\DeleteDeliveryRiderAction;
use App\Actions\DeliveryRider\UpdateDeliveryRiderAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeliveryRider\StoreDeliveryRiderRequest;
use App\Http\Requests\DeliveryRider\UpdateDeliveryRiderRequest;
use App\Http\Resources\DeliveryRiderResource;
use App\Models\DeliveryRider;
use App\Repositories\DeliveryRiderRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeliveryRiderController extends Controller
{
    public function __construct(protected DeliveryRiderRepository $repository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['available', 'top_rated', 'transportation_way', 'search', 'per_page']);

        $riders = $this->repository->all($filters);

        return response()->json([
            'success' => true,
            'data'    => DeliveryRiderResource::collection($riders),
            'meta'    => [
                'current_page' => $riders->currentPage(),
                'last_page'    => $riders->lastPage(),
                'total'        => $riders->total(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeliveryRiderRequest $request, CreateDeliveryRiderAction $action): JsonResponse
    {
        $rider = $action->execute($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء عامل التوصيل بنجاح',
            'data'    => new DeliveryRiderResource($rider),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(DeliveryRider $deliveryRider): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new DeliveryRiderResource($deliveryRider),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeliveryRiderRequest $request, DeliveryRider $deliveryRider, UpdateDeliveryRiderAction $action): JsonResponse
    {
        $rider = $action->execute($deliveryRider, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث بيانات عامل التوصيل بنجاح',
            'data'    => new DeliveryRiderResource($rider),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeliveryRider $deliveryRider, DeleteDeliveryRiderAction $action): JsonResponse
    {
        $action->execute($deliveryRider);

        return response()->json([
            'success' => true,
            'message' => 'تم حذف عامل التوصيل بنجاح',
        ]);
    }
}
