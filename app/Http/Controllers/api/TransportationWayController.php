<?php

namespace App\Http\Controllers\Api;

use App\Enums\TransportationWay;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class TransportationWayController extends Controller
{
    /**
     * Handle the incoming request.
     * بيرجع كل وسائل المواصلات المتاحة (value + label)
     */
    public function __invoke(): JsonResponse
    {
        $ways = collect(TransportationWay::cases())->map(fn (TransportationWay $way) => [
            'value' => $way->value,
            'label' => $way->label(),
        ]);

        return response()->json([
            'success' => true,
            'data'    => $ways,
        ]);
    }
}
