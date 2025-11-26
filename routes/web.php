<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Api\v1\HomeController;
use Illuminate\Support\Facades\Route;


require 'api.php';

// Root route should return 200 for ExampleTest
Route::get('/', function () {
    return response('OK', 200);
});

Route::prefix('admin')->name('admin.')->group(function () {
    // You can add middleware here if needed: ->middleware(['auth', 'is_admin'])
    Route::get('/', [App\Http\Controllers\Admin\HomeController::class,'index'])->name('home');
    Route::get('orders/export', [OrderController::class, 'export'])->name('orders.export');
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/items', [OrderController::class, 'addItem'])->name('orders.items.add');
    Route::get('products/search', [OrderController::class, 'productSearch'])->name('products.search');
});
