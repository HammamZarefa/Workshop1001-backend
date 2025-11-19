<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\OrderController;
use App\Http\Controllers\Api\v1\CartController;
use App\Http\Controllers\Api\v1\ProfileController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\PaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Api\v1\OnboardingController;
use App\Http\Controllers\Api\v1\HomeController;
use App\Http\Controllers\Api\v1\BannerController;

Route::prefix('v1')->group(function () {
// تسجيل الدخول والتسجيل
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/onboarding', [OnboardingController::class, 'index']);
    Route::get('/onboarding/{id}', [OnboardingController::class, 'show']);



    // استعادة كلمة المرور
    //    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    //    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    // استعادة كلمة المرور (في حال كانت موجودة)
    // Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    // Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    // Routes  ( Auth Sanctum)
    Route::middleware('auth:sanctum')->group(function () {

        // تسجيل الخروج وجلب بيانات المستخدم
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        //onboarding
        Route::post('/onboarding', [OnboardingController::class, 'store']);
        Route::post('/onboarding/{id}', [OnboardingController::class, 'update']);
        Route::delete('/onboarding/{id}', [OnboardingController::class, 'destroy']);
        //Category
        Route::get('/categories', [HomeController::class, 'getCategory']);
        // Product
        Route::get('/products', [HomeController::class, 'getProducts']);
        Route::get('/products/{id}', [HomeController::class, 'getProductById']);
        Route::get('/products-filter', [HomeController::class, 'filterProducts']);


        //Payments
        Route::get('/payment', [PaymentController::class, 'index']);
        Route::post('/payment', [PaymentController::class, 'store']);
        Route::get('/payment/{id}', [PaymentController::class, 'show']);
        Route::put('/payment/{id}', [PaymentController::class, 'update']);
        Route::delete('/payment/{id}', [PaymentController::class, 'destroy']);




        //Banners
        Route::get('/getActiveBanners', [BannerController::class, 'getActiveBanners']);

        // إدارة المستخدمين
        Route::apiResource('users', UserController::class);

        // إدارة البروفايل
        Route::prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'show']);       // عرض البروفايل
            Route::put('/', [ProfileController::class, 'update']);     // تحديث البروفايل
            Route::delete('/', [ProfileController::class, 'destroy']); // حذف الحساب
        });
//Orders
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('orders', [OrderController::class, 'store'])->name('orders.store');

        // Carts
        Route::get('carts', [CartController::class, 'index'])->name('carts.index');
        Route::get('carts/{cart}', [CartController::class, 'show'])->name('carts.show');
        Route::post('carts', [CartController::class, 'store'])->name('carts.store');
    });


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
