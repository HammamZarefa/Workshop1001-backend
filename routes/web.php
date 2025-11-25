<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AdminAuthController;
/*
Route::get('/', function () {
    return view('dashboard');
});*/

// Admin Login Form
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])
    ->name('admin.login.form');

// Admin Login
Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->name('admin.login');




// Protected Admin Routes
Route::middleware(['auth', 'is_admin'])->group(function () {

 // Dashboard
    Route::get('/admin/dashboard', function () {
        return view('dashboard');
    })->name('admin.dashboard');
    
    // Logout
    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
        ->name('admin.logout');
});

require 'api.php';
