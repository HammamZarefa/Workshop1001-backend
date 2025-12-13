<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\PaymentController;

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
        Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status.update');
        Route::post('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

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

        // =======================
        // Categories
        // =======================
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::post('/categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');

        // =======================
        // Banners
        // =======================
        Route::resource('banners', BannerController::class);

        // =======================
        // Roles
        // =======================
        Route::resource('roles', RoleController::class);

        // =======================
        // Permissions
        // =======================
        Route::resource('permissions', PermissionController::class);

        // =======================
        // Coupons
        // =======================
        Route::resource('coupons', CouponController::class);


    Route::resource('coupons',CouponController::class);
        // Payment  
    Route::post('payments/{payment}/refund', [PaymentController::class, 'refund'])->name('payments.refund');
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/failed', [PaymentController::class, 'failed'])->name('payments.failed');
    Route::get('payments/statistics', [PaymentController::class, 'statistics'])->name('payments.statistics');
    Route::get('payments/reconciliation', [PaymentController::class, 'reconciliation'])->name('payments.reconciliation');
    Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    });
