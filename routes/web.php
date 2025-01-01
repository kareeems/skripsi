<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\InstalmentController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/tagihan', [App\Http\Controllers\HomeController::class, 'tagihan'])->name('tagihan');
Route::get('/riwayat', [App\Http\Controllers\HomeController::class, 'riwayat'])->name('riwayat');
Route::get('/bantuan', [App\Http\Controllers\HomeController::class, 'bantuan'])->name('bantuan');
Route::get('/transaksi_online', [App\Http\Controllers\HomeController::class, 'transaksi'])->name('transaksi_online');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('role:teacher,admin')->name('dashboard');
Route::resource('items', ItemController::class)->middleware('role:teacher,admin');
Route::resource('users', UserController::class)->middleware('role:teacher,admin');
Route::resource('transactions', TransactionController::class);
Route::patch('/instalments/{instalment}/pay', [InstalmentController::class, 'pay'])->name('instalments.pay');
Route::post('/payments/charge', [PaymentController::class, 'createCharge'])->name('payment.charge');
Route::post('/payments/callback', [PaymentController::class, 'callback'])->name('payment.callback');
