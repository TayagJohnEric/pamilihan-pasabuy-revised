<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\RiderAuthController;
use App\Http\Controllers\Auth\VendorAuthController;

use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Customer\CustomerPasswordController;
use App\Http\Controllers\Customer\CustomerProfileController;
use App\Http\Controllers\Customer\CustomerSavedAddressController;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminRiderApplicationController;
use App\Http\Controllers\Admin\AdminVendorApplicationController;






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

Route::get('/vendor-application', [VendorAuthController::class, 'create'])->name('vendor-applications.create');
Route::post('/vendor-application', [VendorAuthController::class, 'store'])->name('vendor-applications.store');

Route::get('/vendor/dashboard', function () {
    return view('vendor.dashboard.dashboard');
});










//Rider Route
Route::get('/rider-application', [RiderAuthController::class, 'create'])->name('rider-applications.create');
Route::post('/rider-application', [RiderAuthController::class, 'store'])->name('rider-applications.store');

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

Route::prefix('admin')->group(function () {
    Route::get('users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
});

// Rider Applications Index
Route::get('admin/rider-applications', [AdminRiderApplicationController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.rider_applications.index');

// Rider Applications Show
Route::get('admin/rider-applications/{rider_application}', [AdminRiderApplicationController::class, 'show'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.rider_applications.show');

// Rider Applications Destroy
Route::delete('admin/rider-applications/{rider_application}', [AdminRiderApplicationController::class, 'destroy'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.rider_applications.destroy');

// Create Rider from Application
Route::get('admin/rider-applications/{id}/create-rider', [AdminRiderApplicationController::class, 'createRiderFromApplication'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.rider_applications.createRider');

// Store Rider from Application
Route::post('admin/rider-applications/{id}/store-rider', [AdminRiderApplicationController::class, 'storeRiderFromApplication'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.rider_applications.storeRider');

    // Vendor Applications Index
Route::get('admin/vendor-applications', [AdminVendorApplicationController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.vendor_applications.index');

// Vendor Applications Show
Route::get('admin/vendor-applications/{vendor_application}', [AdminVendorApplicationController::class, 'show'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.vendor_applications.show');

// Vendor Applications Destroy
Route::delete('admin/vendor-applications/{vendor_application}', [AdminVendorApplicationController::class, 'destroy'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.vendor_applications.destroy');

// Create Vendor from Application
Route::get('admin/vendor-applications/{id}/create-vendor', [AdminVendorApplicationController::class, 'createVendorFromApplication'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.vendor_applications.createVendor');

// Store Vendor from Application
Route::post('admin/vendor-applications/{id}/store-vendor', [AdminVendorApplicationController::class, 'storeVendorFromApplication'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.vendor_applications.storeVendor');

