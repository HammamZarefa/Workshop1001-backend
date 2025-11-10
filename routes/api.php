<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\ProfileController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    // تسجيل الدخول والتسجيل
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // استعادة كلمة المرور (في حال كانت موجودة)
    // Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    // Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    // Routes محمية (تحتاج Auth Sanctum)
    Route::middleware('auth:sanctum')->group(function () {

        // تسجيل الخروج وجلب بيانات المستخدم
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']); // معلومات المستخدم

        // إدارة المستخدمين
        Route::apiResource('users', UserController::class);

        // إدارة البروفايل
        Route::prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'show']);       // عرض البروفايل
            Route::put('/', [ProfileController::class, 'update']);     // تحديث البروفايل
            Route::delete('/', [ProfileController::class, 'destroy']); // حذف الحساب
        });
    });
});
