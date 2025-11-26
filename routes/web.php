<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Api\v1\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});
require 'api.php';
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
Route::middleware(['auth','is_admin'])
    ->prefix('admin')
    ->name('admin.products.')
    ->group(function(){
        //index
        Route::get('/products', [AdminProductController::class, 'index'])->name('index');
        //create
        Route::get('/products/create', [AdminProductController::class, 'create'])->name('create');;
        //edit
        Route::get('/products/{product}/edit', [AdminProductController::class, 'edit']) ->name('edit');;
        //store
        Route::post('/products', [AdminProductController::class, 'store'])->name('store');
        //update
        Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('update');

        // destroy
        Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('destroy');
        //stock(update quantity)
        Route::patch('/products/{id}/stock', [AdminProductController::class, 'updateStock'])->name('updateStock');
    });
