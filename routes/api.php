<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\ProfileController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\OnboardingController;
use App\Http\Controllers\HomeController;
Route::prefix('v1')->group(function () {

        // تسجيل الدخول والتسجيل
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        //onboarding
        Route::get('/onboarding', [OnboardingController::class, 'index']);
        Route::get('/onboarding/{id}', [OnboardingController::class, 'show']);
        Route::get('/categories', [HomeController::class, 'getCategory']);

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
