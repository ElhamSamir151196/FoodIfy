<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\Admin\GetAllUsersAction;
use App\Actions\Admin\GetUserDetailsAction;
use App\Actions\Admin\ToggleUserStatusAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserWithOrdersResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;

    public function index(Request $request, GetAllUsersAction $action): JsonResponse
    {
        $users = $action->execute($request->only(['search', 'role']));

        return $this->success(data: [
            'users' => UserResource::collection($users),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page'    => $users->lastPage(),
                'per_page'     => $users->perPage(),
                'total'        => $users->total(),
            ],
        ]);
    }

    public function show(int $id, GetUserDetailsAction $action): JsonResponse
    {
        $user = $action->execute($id);

        if (!$user) {
            return $this->error('User not found.', 404);
        }

        return $this->success(data: ['user' => new UserWithOrdersResource($user)]);
    }

    public function toggleStatus(User $user, ToggleUserStatusAction $action): JsonResponse
    {
        $user = $action->execute($user);

        return $this->success(
            data: ['user' => new UserResource($user)],
            message: $user->is_active ? 'User activated.' : 'User deactivated.'
        );
    }
}