<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\OrderController;
use App\Http\Controllers\Api\v1\CartController;
use App\Http\Controllers\Api\v1\ProfileController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\PaymentController;
use App\Http\Controllers\Api\v1\OnboardingController;
use App\Http\Controllers\Api\v1\HomeController;
use App\Http\Controllers\Api\v1\BannerController;
use App\Http\Controllers\Api\v1\NotificationController;



Route::prefix('v1')->group(function () {

    // -----------------------------
    // Public Auth + Onboarding
    // -----------------------------
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:6,1');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:6,1');

    Route::get('/onboarding', [OnboardingController::class, 'index']);
    Route::get('/onboarding/{id}', [OnboardingController::class, 'show']);

    //social login
    Route::post('auth/social-login', [AuthController::class, 'socialLogin']);
    // -----------------------------
    // Protected Routes
    // -----------------------------
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);

        // Onboarding CRUD
        Route::post('/onboarding', [OnboardingController::class, 'store']);
        Route::post('/onboarding/{id}', [OnboardingController::class, 'update']);
        Route::delete('/onboarding/{id}', [OnboardingController::class, 'destroy']);

        // Categories
        Route::get('/categories', [HomeController::class, 'getCategory']);

        // Products
        Route::get('/products', [HomeController::class, 'getProducts']);
        Route::get('/products/{id}', [HomeController::class, 'getProductById']);
        Route::get('/products-filter', [HomeController::class, 'filterProducts']);

        // Ratings
        Route::post('/ratings', [HomeController::class, 'addProductRating']);

        // Payments
        Route::get('/payment', [PaymentController::class, 'index']);
        Route::post('/payment', [PaymentController::class, 'store']);
        Route::get('/payment/{id}', [PaymentController::class, 'show']);

        // Banners
        Route::get('/getActiveBanners', [BannerController::class, 'getActiveBanners']);

        // Users (Admin-like API)
       Route::apiResource('users', UserController::class);

        // Profile
        Route::prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'show']);
            Route::put('/', [ProfileController::class, 'update']);
            Route::delete('/', [ProfileController::class, 'destroy']);
            Route::post('/upload-image', [ProfileController::class, 'uploadProfileImage']);
        });

        // Orders
        Route::get('orders', [OrderController::class, 'myOrders'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('orders', [OrderController::class, 'store'])->name('orders.store');

        // Carts
        Route::get('carts', [CartController::class, 'activeCart'])->name('carts.index');
        Route::get('carts/{cart}', [CartController::class, 'show'])->name('carts.show');
        Route::post('carts', [CartController::class, 'store'])->name('carts.store');
        Route::delete('carts/items/{item}', [CartController::class, 'removeItem'])->name('carts.items.remove');

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
        // FCM Token
        Route::post('/fcm-token', [AuthController::class, 'updateFcmToken']);


    });




    // -----------------------------
    // Artisan Executor
    // -----------------------------
    Route::get('/artisan/{command}', function ($command) {
        try {
            $exitCode = Artisan::call($command);
            return response()->json([
                'status' => true,
                'message' => "Command '$command' executed successfully",
                'exit_code' => $exitCode,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    });
});
