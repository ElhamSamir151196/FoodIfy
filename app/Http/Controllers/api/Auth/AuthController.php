<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Auth\ForgetPasswordAction;
use App\Actions\Auth\LoginAction;
use App\Actions\Auth\RegisterAction;
use App\Actions\Auth\ResetPasswordAction;
use App\Actions\Auth\VerifyRegisterOtpAction;
use App\Actions\Auth\VerifyResetOtpAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgetPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Http\Resources\UserResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    // ── Register: Step 1 ──────────────────────────────────
    public function register(RegisterRequest $request, RegisterAction $action): JsonResponse
    {
        $action->execute($request->validated());

        return $this->success(
            message: 'OTP sent. Please verify your phone number.'
        );
    }

    // ── Register: Step 2 ──────────────────────────────────
    public function verifyRegister(VerifyOtpRequest $request, VerifyRegisterOtpAction $action): JsonResponse
    {
        $result = $action->execute($request->phone, $request->otp);

        if (!$result['verified']) {
            $message = $result['reason'] === 'session_expired'
                ? 'Session expired. Please register again.'
                : 'Invalid or expired OTP.';

            return $this->error($message, 422);
        }

        return $this->success(
            data: ['user' => new UserResource($result['user']), 'token' => $result['token']],
            message: 'Account created successfully.',
            statusCode: 201
        );
    }

    // ── Login ─────────────────────────────────────────────
    public function login(LoginRequest $request, LoginAction $action): JsonResponse
    {
        $result = $action->execute($request->phone, $request->password);

        if (!$result['success']) {
            $message = $result['reason'] === 'inactive'
                ? 'Your account has been deactivated.'
                : 'The provided credentials are incorrect.';

            $code = $result['reason'] === 'inactive' ? 403 : 401;

            return $this->error($message, $code);
        }

        return $this->success(
            data: ['user' => new UserResource($result['user']), 'token' => $result['token']],
            message: 'Logged in successfully.'
        );
    }

    // ── Forget Password: Step 1 ───────────────────────────
    public function forgetPassword(ForgetPasswordRequest $request, ForgetPasswordAction $action): JsonResponse
    {
        $action->execute($request->phone);

        return $this->success(message: 'OTP sent to your phone number.');
    }

    // ── Forget Password: Step 2 ───────────────────────────
    public function verifyResetOtp(VerifyOtpRequest $request, VerifyResetOtpAction $action): JsonResponse
    {
        if (!$action->execute($request->phone, $request->otp)) {
            return $this->error('Invalid or expired OTP.', 422);
        }

        return $this->success(message: 'OTP verified. You can now reset your password.');
    }

    // ── Forget Password: Step 3 ───────────────────────────
    public function resetPassword(ResetPasswordRequest $request, ResetPasswordAction $action): JsonResponse
    {
        if (!$action->execute($request->phone, $request->password)) {
            return $this->error('Please verify your OTP first.', 403);
        }

        return $this->success(message: 'Password reset successfully.');
    }

    // ── Logout ────────────────────────────────────────────
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(message: 'Logged out successfully.');
    }

    // ── Me ────────────────────────────────────────────────
    public function me(Request $request): JsonResponse
    {
        return $this->success(
            data: ['user' => new UserResource($request->user())]
        );
    }
}