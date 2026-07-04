<?php

namespace App\Http\Controllers\Api;

use App\Actions\Profile\ChangePasswordAction;
use App\Actions\Profile\GetProfileAction;
use App\Actions\Profile\UpdateAvatarAction;
use App\Actions\Profile\UpdateProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ChangePasswordRequest;
use App\Http\Requests\Profile\UpdateAvatarRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use ApiResponse;

    public function show(Request $request, GetProfileAction $action): JsonResponse
    {
        $user = $action->execute($request->user());

        return $this->success(data: ['user' => new UserResource($user)]);
    }

    public function update(UpdateProfileRequest $request, UpdateProfileAction $action): JsonResponse
    {
        $user = $action->execute($request->user(), $request->validated());

        return $this->success(
            data: ['user' => new UserResource($user)],
            message: 'Profile updated successfully.'
        );
    }

    public function updateAvatar(UpdateAvatarRequest $request, UpdateAvatarAction $action): JsonResponse
    {
        $user = $action->execute($request->user(), $request->file('avatar'));

        return $this->success(
            data: ['user' => new UserResource($user)],
            message: 'Avatar updated successfully.'
        );
    }

    public function changePassword(ChangePasswordRequest $request, ChangePasswordAction $action): JsonResponse
    {
        $changed = $action->execute(
            $request->user(),
            $request->current_password,
            $request->password
        );

        if (!$changed) {
            return $this->error('Current password is incorrect.', 422);
        }

        return $this->success(message: 'Password changed successfully.');
    }
}