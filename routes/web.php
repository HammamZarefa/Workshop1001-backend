<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/admin/login', function () {
    return view('admin.auth.login');
});
require 'api.php';
