<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminProductController;
Route::get('/', function () {
    return view('dashboard');
});
require 'api.php';
// Route::middleware(['auth','is_admin'])
//     ->prefix('admin')
//     ->group(function(){
//     //index
//     Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.index');;
//     //create
//     Route::get('/products/create', [AdminProductController::class, 'create']);
//     //edit
//     Route::get('/products/{product}/edit', [AdminProductController::class, 'edit']);
//     //store
//     Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
//     //update
//     Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');

//     // destroy
//     Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');
//     //stock(update quantity)
//     Route::patch('/products/{id}/stock', [AdminProductController::class, 'updateStock'])->name('products.updateStock');
//     });
 Route::prefix('admin')->group(function(){
    Route::get('/products', [AdminProductController::class, 'index']) ->name('admin.products.index');;
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');;
    Route::post('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
     Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
     Route::post('/products', [AdminProductController::class, 'store'])->name('admin.products.store');
     Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('admin.products.update');
     Route::patch('/products/{id}/stock', [AdminProductController::class, 'updateStock'])->name('admin.products.updateStock');

 });
