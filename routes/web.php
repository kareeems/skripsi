<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('role:teacher,admin')->name('dashboard');
Route::resource('items', ItemController::class)->middleware('role:teacher,admin');
Route::resource('users', UserController::class)->middleware('role:teacher,admin');;
