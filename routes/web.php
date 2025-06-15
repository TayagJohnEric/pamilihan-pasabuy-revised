<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\AdminAuthController;

use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Customer\CustomerPasswordController;
use App\Http\Controllers\Customer\CustomerProfileController;
use App\Http\Controllers\Customer\CustomerSavedAddressController;

use App\Http\Controllers\Admin\AdminDashboardController;



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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [CustomerProfileController::class, 'show'])->name('customer.profile.show');
    Route::get('/profile/edit', [CustomerProfileController::class, 'edit'])->name('customer.profile.edit');
    Route::put('/profile/update', [CustomerProfileController::class, 'update'])->name('customer.profile.update');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile/password/edit', [CustomerPasswordController::class, 'edit'])->name('customer.password.edit');
    Route::put('/profile/password', [CustomerPasswordController::class, 'update'])->name('customer.password.update');
});
Route::middleware(['auth'])->group(function () {
    // List all saved addresses
    Route::get('/saved_addresses', [CustomerSavedAddressController::class, 'index'])->name('customer.saved_addresses.index');
    // Show form to create a new address
    Route::get('/saved_addresses/create', [CustomerSavedAddressController::class, 'create'])->name('customer.saved_addresses.create');
    // Store new address
    Route::post('/saved_addresses', [CustomerSavedAddressController::class, 'store'])->name('customer.saved_addresses.store');
    // Show form to edit existing address
    Route::get('/saved_addresses/{saved_address}/edit', [CustomerSavedAddressController::class, 'edit'])->name('customer.saved_addresses.edit');
    // Update the address
    Route::put('/saved_addresses/{saved_address}', [CustomerSavedAddressController::class, 'update'])->name('customer.saved_addresses.update');
    // Delete the address
    Route::delete('/saved_addresses/{saved_address}', [CustomerSavedAddressController::class, 'destroy'])->name('customer.saved_addresses.destroy');
});










//Vendor Route
Route::get('/vendor/dashboard', function () {
    return view('vendor.dashboard.dashboard');
});










//Rider Route
Route::get('/rider/dashboard', function () {
    return view('rider.dashboard.dashboard');
});







//Admin Route
//Admin Login and Register
Route::middleware('guest')->group(function () {
    Route::get('/auth/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/auth/admin/login', [AdminAuthController::class, 'login']);
});
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->middleware('auth')->name('admin.logout');


Route::get('/admin/dashboard', [AdminDashboardController::class, 'dashboard'])->middleware(['auth', 'role:admin'])->name('admin.dashboard');


