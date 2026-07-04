<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\Admin\GetDashboardStatsAction;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    use ApiResponse;

    public function stats(GetDashboardStatsAction $action): JsonResponse
    {
        return $this->success(data: $action->execute());
    }
}