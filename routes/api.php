<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

// تسجيل الدخول والتسجيل
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes محمية
Route::middleware('auth:sanctum')->group(function () {
    // تسجيل الخروج وجلب بيانات المستخدم
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']); // معلومات أساسية للمستخدم

    // إدارة البروفايل
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);       // عرض البروفايل
        Route::put('/', [ProfileController::class, 'update']);     // تحديث البروفايل
        Route::delete('/', [ProfileController::class, 'destroy']); // حذف الحساب
    });
});
