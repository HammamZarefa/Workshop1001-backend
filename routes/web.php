<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
/*
Route::get('/', function () {
    return view('dashboard');
});*/

// Admin Login Form
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])
    ->name('admin.login.form');

// Admin Login
Route::post('/admin/login', [AuthController::class, 'login'])
    ->name('admin.login');




// Protected Admin Routes
Route::middleware(['auth', 'is_admin'])->group(function () {

 // Dashboard
    Route::get('/admin/dashboard', function () {
        return view('dashboard');
    })->name('admin.dashboard');


    // Logout
    Route::post('/admin/logout', [AuthController::class, 'logout'])
        ->name('admin.logout');
});

require 'api.php';
