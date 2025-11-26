<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminProductController;
Route::get('/', function () {
    return view('dashboard');
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

