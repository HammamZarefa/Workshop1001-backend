<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;

// ==============================
// Public Root
// ==============================
Route::get('/', fn() => response('OK', 200));


// ==============================
// Admin Auth (NO middleware here)
// ==============================
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])
    ->name('admin.login.form');

Route::post('/admin/login', [AuthController::class, 'login'])
    ->name('admin.login');


// ==============================
// Admin Protected Area
// ==============================
Route::middleware(['is_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard + Home
        Route::get('/', [HomeController::class, 'index'])->name('home');


        // Logout
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


        // =======================
        // Orders
        // =======================
        Route::get('orders/export', [OrderController::class, 'export'])->name('orders.export');
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('orders/{order}/items', [OrderController::class, 'addItem'])->name('orders.items.add');

        // Product search
        Route::get('products/search', [OrderController::class, 'productSearch'])->name('products.search');


        // =======================
        // Products CRUD
        // =======================
        Route::resource('products', ProductController::class);

        // Stock update
        Route::patch('products/{id}/stock', [ProductController::class, 'updateStock'])
            ->name('products.updateStock');

        // Gallery delete
        Route::delete('products/gallery/{media}', [ProductController::class, 'destroyGallery'])
            ->name('products.gallery.delete');
    });
