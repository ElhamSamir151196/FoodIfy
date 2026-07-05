<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProfileController;

// routes are defined in a separate file for better organization
require __DIR__ . '/auth.php'; // Auth routes
require __DIR__ . '/category.php'; // Category  routes
require __DIR__ . '/meal.php'; // Meal routes
require __DIR__ . '/favorite.php'; // Favorite routes
require __DIR__ . '/cart.php'; // Cart routes
require __DIR__ . '/order.php'; // Order routes
require __DIR__ . '/payment.php'; // Payment routes
require __DIR__ . '/delivery_rider.php'; // Delivery Rider routes
/*
|--------------------------------------------------------------------------
| Authenticated Client Routes (Cart, Favorites, Profile, Notifications)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
   
    // ── Profile ───────────────────────────
    Route::get('profile',                    [ProfileController::class, 'show']);
    Route::put('profile',                    [ProfileController::class, 'update']);
    Route::post('profile/avatar',            [ProfileController::class, 'updateAvatar']);
    Route::post('profile/change-password',   [ProfileController::class, 'changePassword']);

    // ── Notifications ─────────────────────
    Route::get('notifications',              [NotificationController::class, 'index']);
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::patch('notifications/{id}/read',  [NotificationController::class, 'markAsRead']);
    Route::patch('notifications/read-all',   [NotificationController::class, 'markAllAsRead']);
});



/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'is_admin'])->prefix('admin')->group(function () {

    // ── Dashboard ─────────────────────────
    Route::get('dashboard/stats', [DashboardController::class, 'stats']);

    // ── Users ─────────────────────────────
    Route::get('users',                       [AdminUserController::class, 'index']);
    Route::get('users/{id}',                  [AdminUserController::class, 'show']);
    Route::patch('users/{user}/toggle-status',[AdminUserController::class, 'toggleStatus']);
});


/*

use App\Http\Controllers\api\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register',              [AuthController::class, 'register']);
    Route::post('register/verify',       [AuthController::class, 'verifyRegister']);
    Route::post('login',                 [AuthController::class, 'login']);
    Route::post('forget-password',       [AuthController::class, 'forgetPassword']);
    Route::post('forget-password/verify',[AuthController::class, 'verifyResetOtp']);
    Route::post('reset-password',        [AuthController::class, 'resetPassword']);
});

Route::middleware(['auth:sanctum', 'active'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me',      [AuthController::class, 'me']);
    });
});


// ─────────────────────────────────────────
// Public Routes
// ─────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});



use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\MealController;

// ── Public ────────────────────────────────
Route::get('categories', [CategoryController::class, 'index']);
Route::get('categories/{id}', [CategoryController::class, 'show']);
Route::get('meals', [MealController::class, 'index']);
Route::get('meals/{id}', [MealController::class, 'show']);

// ── Admin ─────────────────────────────────
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::post('categories', [CategoryController::class, 'store']);
    Route::put('categories/{category}', [CategoryController::class, 'update']);
    Route::delete('categories/{category}', [CategoryController::class, 'destroy']);

    Route::post('meals', [MealController::class, 'store']);
    Route::put('meals/{meal}', [MealController::class, 'update']);
    Route::delete('meals/{meal}', [MealController::class, 'destroy']);
    Route::patch('meals/{meal}/toggle-availability', [MealController::class, 'toggleAvailability']);
});


use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\FavoriteController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('cart', [CartController::class, 'index']);
    Route::post('cart', [CartController::class, 'store']);
    Route::put('cart/{mealId}', [CartController::class, 'update']);
    Route::delete('cart/{mealId}', [CartController::class, 'destroy']);
    Route::delete('cart', [CartController::class, 'clear']);

    Route::get('favorites', [FavoriteController::class, 'index']);
    Route::post('favorites/toggle', [FavoriteController::class, 'toggle']);
});

use App\Http\Controllers\Api\OrderController;

Route::middleware(['auth:sanctum', 'client'])->group(function () {
    Route::get('orders', [OrderController::class, 'index']);
    Route::get('orders/{id}', [OrderController::class, 'show']);
    Route::post('checkout', [OrderController::class, 'checkout']);
});

use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PaymentMethodController;

Route::middleware(['auth:sanctum', 'client'])->group(function () {
    Route::post('orders/{order}/pay', [PaymentController::class, 'initiate']);

    Route::get('payment-methods', [PaymentMethodController::class, 'index']);
    Route::post('payment-methods', [PaymentMethodController::class, 'store']);
    Route::patch('payment-methods/{id}/default', [PaymentMethodController::class, 'setDefault']);
    Route::delete('payment-methods/{id}', [PaymentMethodController::class, 'destroy']);
});

// Paymob webhook — no auth, Paymob calls this server-to-server
Route::post('payments/callback', [PaymentController::class, 'callback']);


Route::middleware(['auth:sanctum', 'client'])->group(function () {
    Route::post('orders/{order}/pay', [PaymentController::class, 'initiate']);

    Route::get('payment-methods', [PaymentMethodController::class, 'index']);
    Route::post('payment-methods', [PaymentMethodController::class, 'store']);
    Route::patch('payment-methods/{id}/default', [PaymentMethodController::class, 'setDefault']);
    Route::delete('payment-methods/{id}', [PaymentMethodController::class, 'destroy']);
});

// Paymob webhook — no auth, Paymob calls this server-to-server
Route::post('payments/callback', [PaymentController::class, 'callback']);


use App\Actions\Order\UpdateOrderStatusAction;
use App\Http\Requests\Order\UpdateOrderStatusRequest;
use App\Traits\ApiResponse;

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::patch('orders/{order}/status', function (
        \App\Models\Order $order,
        UpdateOrderStatusRequest $request,
        UpdateOrderStatusAction $action
    ) {
        $updated = $action->execute($order->id, \App\Enums\OrderStatus::from($request->status));

        return response()->json([
            'success' => true,
            'message' => 'Order status updated.',
            'data'    => ['order' => new \App\Http\Resources\OrderResource($updated)],
        ]);
    });
});


use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProfileController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [ProfileController::class, 'show']);
    Route::put('profile', [ProfileController::class, 'update']);
    Route::post('profile/avatar', [ProfileController::class, 'updateAvatar']);
    Route::post('profile/change-password', [ProfileController::class, 'changePassword']);

    Route::get('notifications', [NotificationController::class, 'index']);
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::patch('notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::patch('notifications/read-all', [NotificationController::class, 'markAllAsRead']);
});

use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;

Route::middleware(['auth:sanctum', 'is_admin'])->prefix('admin')->group(function () {
    Route::get('dashboard/stats', [DashboardController::class, 'stats']);

    Route::get('orders', [AdminOrderController::class, 'index']);
    Route::get('orders/{id}', [AdminOrderController::class, 'show']);
    Route::patch('orders/{id}/status', [AdminOrderController::class, 'updateStatus']);
    Route::patch('orders/{id}/assign-rider', [AdminOrderController::class, 'assignRider']);

    Route::get('users', [AdminUserController::class, 'index']);
    Route::get('users/{id}', [AdminUserController::class, 'show']);
    Route::patch('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus']);
});

*/