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



        // =======================
        // Categories
        // =======================

    // عرض كل الcatigories (هرمية)
    Route::get('/categories', [CategoryController::class, 'index'])
        ->name('categories.index');

    //عرض صفحة إنشاء catigory جديد
    Route::get('/categories/create', [CategoryController::class, 'create'])
    ->name('categories.create');

    // عرض صفحة كاتيجوري معيّن
    Route::get('/categories/{id}', [CategoryController::class, 'show'])
        ->name('categories.show');


    // إنشاء catigory جديد
    Route::post('/categories', [CategoryController::class, 'store'])
        ->name('categories.store');

    // صفحة تعديل catigory
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])
        ->name('categories.edit');

    // تحديث catigory
    Route::put('/categories/{id}', [CategoryController::class, 'update'])
        ->name('categories.update');

    // حذف catigory
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])
        ->name('categories.destroy');

    // إعادة ترتيب  (Reorder)
    Route::post('/categories/reorder', [CategoryController::class, 'reorder'])
        ->name('categories.reorder');


        // =======================
        // Banners
        // =======================

    Route::resource('banners',BannerController::class);

    //roles
    Route::resource('roles', RoleController::class);

    //permissions
    Route::resource('permissions', PermissionController::class);









    });
