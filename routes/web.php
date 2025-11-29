<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Api\v1\HomeController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;

// Admin Route Redirect Logic
Route::get('/admin', function () {
    // إذا المستخدم مسجل دخول وكان أدمن → dashboard
    if (auth()->check() && auth()->user()->is_admin) {
        return redirect()->route('admin.dashboard');
    }

    // غير مسجل دخول → login
    return redirect()->route('admin.login.form');
})->name('admin.home');

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
Route::get('/', function () {
    return response('OK', 200);
});

Route::prefix('admin')->group(function(){
    Route::get('/products', [ProductController::class, 'index']) ->name('admin.products.index');;
    Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');;
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::patch('/products/{id}/stock', [ProductController::class, 'updateStock'])->name('admin.products.updateStock');
    Route::delete('/admin/products/gallery/{media}', [ProductController::class, 'destroyGallery'])
        ->name('admin.products.gallery.delete');

});
