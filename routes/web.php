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
    Route::resource('products', AdminProductController::class);
    });