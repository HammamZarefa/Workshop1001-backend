<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Api\v1\HomeController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});
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
//Route::
//    prefix('admin')
//    ->name('admin.products.')
//    ->group(function(){
//        //index
//        Route::get('/products', [ProductController::class, 'index'])->name('index');
//        //create
//      //  Route::get('/products/create', [ProductController::class, 'create'])->name('create');;
//        //edit
//        Route::get('/products/{product}/edit', [ProductController::class, 'edit']) ->name('edit');;
//        //store
//        Route::post('/products', [ProductController::class, 'store'])->name('store');
//        //update
//        Route::put('/products/{product}', [ProductController::class, 'update'])->name('update');
//
//        // destroy
//        Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('destroy');
//        //stock(update quantity)
//        Route::patch('/products/{id}/stock', [ProductController::class, 'updateStock'])->name('updateStock');
//    });
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
