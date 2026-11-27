<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.perform');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
Route::post('/profile/topup', [AuthController::class, 'topup'])->name('profile.topup');
Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
Route::post('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');

Route::get('/order', [OrderController::class, 'create'])->name('order');
Route::post('/order', [OrderController::class, 'store'])->name('order.store');
Route::post('/order/{order}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');

Route::get('/warehouses', function () {
    return view('pages.warehouses');
})->name('warehouses');

Route::get('/price', function () {
    return view('pages.price');
})->name('price');

Route::get('/shop', function () {
    return view('pages.shop');
})->name('shop');

Route::get('/faq', function () {
    return view('pages.faq');
})->name('faq');

Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking');

Route::post('/payment/create', [PaymentController::class, 'create'])->name('payment.create');
Route::get('/payment/confirm/{uuid}', [PaymentController::class, 'confirm'])->name('payment.confirm');

Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/orders/{id}/status', [AdminController::class, 'updateStatus'])->name('admin.orders.status');
});
