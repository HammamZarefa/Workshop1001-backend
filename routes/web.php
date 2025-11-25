<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminProductController;
Route::get('/', function () {
    return view('dashboard');
});
require 'api.php';
Route::middleware(['auth','is_admin'])
    ->prefix('admin')
    ->group(function(){
    //store
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    //update
    Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('products.update');
    // destroy
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');
    //stock(update quantity)
    Route::patch('/products/{id}/stock', [AdminProductController::class, 'updateStock'])->name('products.updateStock');
    });