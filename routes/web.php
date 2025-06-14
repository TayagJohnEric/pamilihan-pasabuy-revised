<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Customer\CustomerDashboardController;


Route::get('/', function () {
    return view('welcome');
});







//Customer Route
//Customer Login and Register
Route::middleware('guest')->group(function () {
    Route::get('/auth/login', [CustomerAuthController::class, 'showLoginForm'])->name('customer.login');
    Route::post('/auth/login', [CustomerAuthController::class, 'login']);

    Route::get('/register', [CustomerAuthController::class, 'showRegisterForm'])->name('customer.register');
    Route::post('/register', [CustomerAuthController::class, 'register']);
});
Route::post('/customer/logout', [CustomerAuthController::class, 'logout'])->middleware('auth')->name('customer.logout');
Route::get('/home', [CustomerDashboardController::class, 'dashboard'])->middleware(['auth', 'role:customer'])->name('customer.dashboard');








//Vendor Route
Route::get('/vendor-dashboard', function () {
    return view('vendor.dashboard.dashboard');
});










//Rider Route
Route::get('/rider-dashboard', function () {
    return view('rider.dashboard.dashboard');
});










//Admin Route
Route::get('/admin-dashboard', function () {
    return view('admin.dashboard.dashboard');
});