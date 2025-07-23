<?php

use Illuminate\Support\Facades\Route;



//Landing Page
Route::get('/', function () {
    return view('welcome');
});



//-----------------------------------------------Customer Route---------------------------------------------------------//

//Customer Login and Register
Route::middleware('guest')->group(function () {
    Route::get('/auth/login', [App\Http\Controllers\Auth\CustomerAuthController::class, 'showLoginForm'])->name('customer.login');
    Route::post('/auth/login', [App\Http\Controllers\Auth\CustomerAuthController::class, 'login']);

    Route::get('/register', [App\Http\Controllers\Auth\CustomerAuthController::class, 'showRegisterForm'])->name('customer.register');
    Route::post('/register', [App\Http\Controllers\Auth\CustomerAuthController::class, 'register']);
});
Route::post('/customer/logout', [App\Http\Controllers\Auth\CustomerAuthController::class, 'logout'])->middleware('auth')->name('customer.logout');

//Customer Dashboard
Route::get('/home', [App\Http\Controllers\Customer\CustomerDashboardController::class, 'dashboard'])->middleware(['auth', 'role:customer'])->name('customer.dashboard');

//User Profile Mangement (Profile, Password, Address)
//Profile Route
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\Customer\CustomerProfileController::class, 'show'])->name('customer.profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\Customer\CustomerProfileController::class, 'edit'])->name('customer.profile.edit');
    Route::put('/profile/update', [App\Http\Controllers\Customer\CustomerProfileController::class, 'update'])->name('customer.profile.update');
});
//Password Route
Route::middleware('auth')->group(function () {
    Route::get('/profile/password/edit', [App\Http\Controllers\Customer\CustomerPasswordController::class, 'edit'])->name('customer.password.edit');
    Route::put('/profile/password', [App\Http\Controllers\Customer\CustomerPasswordController::class, 'update'])->name('customer.password.update');
});
//Address Route
Route::middleware(['auth'])->group(function () {
    Route::get('/saved_addresses', [App\Http\Controllers\Customer\CustomerSavedAddressController::class, 'index'])->name('customer.saved_addresses.index');
    Route::get('/saved_addresses/create', [App\Http\Controllers\Customer\CustomerSavedAddressController::class, 'create'])->name('customer.saved_addresses.create');
    Route::post('/saved_addresses', [App\Http\Controllers\Customer\CustomerSavedAddressController::class, 'store'])->name('customer.saved_addresses.store');
    Route::get('/saved_addresses/{saved_address}/edit', [App\Http\Controllers\Customer\CustomerSavedAddressController::class, 'edit'])->name('customer.saved_addresses.edit');
    Route::put('/saved_addresses/{saved_address}', [App\Http\Controllers\Customer\CustomerSavedAddressController::class, 'update'])->name('customer.saved_addresses.update');
    Route::delete('/saved_addresses/{saved_address}', [App\Http\Controllers\Customer\CustomerSavedAddressController::class, 'destroy'])->name('customer.saved_addresses.destroy');
});










//-----------------------------------------------Vendor Route---------------------------------------------------------//

//Vendor Register Application
Route::get('/vendor-application', [App\Http\Controllers\Auth\VendorAuthController::class, 'create'])->name('vendor-applications.create');
Route::post('/vendor-application', [App\Http\Controllers\Auth\VendorAuthController::class, 'store'])->name('vendor-applications.store');

//Rider Login
 Route::get('/vendor/login', [App\Http\Controllers\Auth\VendorAuthController::class, 'showLoginForm'])->name('vendor.login');
 Route::post('/vendor/login', [App\Http\Controllers\Auth\VendorAuthController::class, 'login']);

Route::post('/vendor/logout', [App\Http\Controllers\Auth\VendorAuthController::class, 'logout'])->middleware('auth')->name('vendor.logout');

// Vendor Dashboard Routes
Route::middleware(['auth', 'role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    // Main Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Vendor\VendorDashboardController::class, 'index'])->name('dashboard');
});

// Vendor Profile Management
Route::middleware(['auth', 'role:vendor'])->group(function () {
    // Vendor Profile Management Routes
    Route::prefix('vendor')->name('vendor.')->group(function () {
        // Shop Profile Routes
        Route::get('/profile', [App\Http\Controllers\Vendor\VendorProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/edit', [App\Http\Controllers\Vendor\VendorProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [App\Http\Controllers\Vendor\VendorProfileController::class, 'update'])->name('profile.update');
        
        // Ratings and Reviews
        Route::get('/shop/ratings', [App\Http\Controllers\Vendor\VendorProfileController::class, 'ratings'])->name('profile.ratings'); 
    });

});
// Product Management Route
Route::middleware(['auth', 'role:vendor'])->prefix('vendor/products')->name('vendor.products.')->group(function () {
    Route::get('/', [App\Http\Controllers\Vendor\VendorProductController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Vendor\VendorProductController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Vendor\VendorProductController::class, 'store'])->name('store');
    Route::get('/{product}', [App\Http\Controllers\Vendor\VendorProductController::class, 'show'])->name('show');
    Route::get('/{product}/edit', [App\Http\Controllers\Vendor\VendorProductController::class, 'edit'])->name('edit');
    Route::put('/{product}', [App\Http\Controllers\Vendor\VendorProductController::class, 'update'])->name('update');
    Route::delete('/{product}', [App\Http\Controllers\Vendor\VendorProductController::class, 'destroy'])->name('destroy');

    // Toggle availability of a specific product
    Route::patch('/{product}/toggle-availability', [App\Http\Controllers\Vendor\VendorProductController::class, 'toggleAvailability'])->name('toggle-availability');
});

// Earning Route
Route::middleware(['auth', 'role:vendor'])
    ->prefix('vendor/earnings')
    ->name('vendor.earnings.')
    ->group(function () {
        Route::get('/', [App\Http\Controllers\Vendor\VendorEarningController::class, 'index'])->name('index');
        Route::get('/payout/{id}', [App\Http\Controllers\Vendor\VendorEarningController::class, 'showPayout'])->name('payout.details');
    });

// Ratings Route
    Route::get('vendor/ratings', [App\Http\Controllers\Vendor\VendorRatingController::class, 'index'])
    ->middleware(['auth', 'role:vendor'])
    ->name('vendor.ratings.index');





//-----------------------------------------------Rider Route---------------------------------------------------------//

//Rider Register Application
Route::get('/rider-application', [App\Http\Controllers\Auth\RiderAuthController::class, 'create'])->name('rider-applications.create');
Route::post('/rider-application', [App\Http\Controllers\Auth\RiderAuthController::class, 'store'])->name('rider-applications.store');

//Rider Login
 Route::get('/rider/login', [App\Http\Controllers\Auth\RiderAuthController::class, 'showLoginForm'])->name('rider.login');
 Route::post('/rider/login', [App\Http\Controllers\Auth\RiderAuthController::class, 'login']);

 Route::post('/rider/logout', [App\Http\Controllers\Auth\RiderAuthController::class, 'logout'])->middleware('auth')->name('rider.logout');

//Rider Dashboard
 Route::get('/rider/dashboard', [App\Http\Controllers\Rider\RiderDashboardController::class, 'dashboard'])->middleware('auth', 'role:rider')->name('rider.dashboard');


//Rider Profile
Route::middleware(['auth', 'role:rider'])->prefix('rider')->name('rider.')->group(function () {
    Route::get('/profile', [App\Http\Controllers\Rider\RiderProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\Rider\RiderProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\Rider\RiderProfileController::class, 'update'])->name('profile.update');
});

//Rider Ratings and Earning/Payouts
Route::middleware(['auth', 'role:rider'])->prefix('rider')->group(function () {
    Route::get('/earnings', [App\Http\Controllers\Rider\RiderEarningsController::class, 'earnings'])->name('rider.earnings');
    Route::get('/payouts', [App\Http\Controllers\Rider\RiderEarningsController::class, 'payouts'])->name('rider.payouts');
    Route::get('/ratings', [App\Http\Controllers\Rider\RiderRatingController::class, 'ratings'])->name('rider.ratings');
});







 
//-----------------------------------------------Admin Route---------------------------------------------------------//

//Admin Login and Register
Route::middleware('guest')->group(function () {
    Route::get('/auth/admin/login', [App\Http\Controllers\Auth\AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/auth/admin/login', [App\Http\Controllers\Auth\AdminAuthController::class, 'login']);
});
Route::post('/admin/logout', [App\Http\Controllers\Auth\AdminAuthController::class, 'logout'])->middleware('auth')->name('admin.logout');


//Admin Dashboard
Route::get('/admin/dashboard', [App\Http\Controllers\Admin\AdminDashboardController::class, 'dashboard'])->middleware(['auth', 'role:admin'])->name('admin.dashboard');


// User Management
// All Users Routes
Route::prefix('admin')->group(function () {
    Route::get('users', [App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('users/{user}/edit', [App\Http\Controllers\Admin\AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('users/{user}', [App\Http\Controllers\Admin\AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('users/{user}', [App\Http\Controllers\Admin\AdminUserController::class, 'destroy'])->name('admin.users.destroy');
});

// Rider Applications Routes
Route::prefix('admin/rider-applications')
    ->name('admin.rider_applications.')
    ->middleware(['auth', 'role:admin'])
    ->controller(App\Http\Controllers\Admin\AdminRiderApplicationController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('{rider_application}', 'show')->name('show');
        Route::delete('{rider_application}', 'destroy')->name('destroy');
        Route::get('{id}/create-rider', 'createRiderFromApplication')->name('createRider');
        Route::post('{id}/store-rider', 'storeRiderFromApplication')->name('storeRider');
    });

// Vendor Applications Routes
Route::prefix('admin/vendor-applications')
    ->name('admin.vendor_applications.')
    ->middleware(['auth', 'role:admin'])
    ->controller( App\Http\Controllers\Admin\AdminVendorApplicationController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('{vendor_application}', 'show')->name('show');
        Route::delete('{vendor_application}', 'destroy')->name('destroy');
        Route::get('{id}/create-vendor', 'createVendorFromApplication')->name('createVendor');
        Route::post('{id}/store-vendor', 'storeVendorFromApplication')->name('storeVendor');
    });

    

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {

    // Orders
    Route::get('orders', [App\Http\Controllers\Admin\AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('orders/{id}', [App\Http\Controllers\Admin\AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::post('orders/{id}/status', [App\Http\Controllers\Admin\AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::post('orders/{id}/assign-rider', [App\Http\Controllers\Admin\AdminOrderController::class, 'assignRider'])->name('admin.orders.assignRider');

    // Vendors
    Route::get('vendors', [App\Http\Controllers\Admin\AdminVendorController::class, 'index'])->name('admin.vendors.index');
    Route::post('vendors/{id}/toggle', [App\Http\Controllers\Admin\AdminVendorController::class, 'toggleStatus'])->name('admin.vendors.toggle-status');

    // Products
    Route::get('products', [App\Http\Controllers\Admin\AdminProductController::class, 'index'])->name('admin.products.index');
    Route::post('products/{id}/toggle', [App\Http\Controllers\Admin\AdminProductController::class, 'toggleAvailability'])->name('admin.products.toggle');
    Route::delete('products/{id}', [App\Http\Controllers\Admin\AdminProductController::class, 'destroy'])->name('admin.products.destroy');
});


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    /** -------------------------
     * Category Management Routes
     * -------------------------- */
    Route::get('categories', [App\Http\Controllers\Admin\AdminCategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/create', [App\Http\Controllers\Admin\AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('categories', [App\Http\Controllers\Admin\AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('categories/{id}', [App\Http\Controllers\Admin\AdminCategoryController::class, 'show'])->name('categories.show');
    Route::get('categories/{id}/edit', [App\Http\Controllers\Admin\AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{id}', [App\Http\Controllers\Admin\AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{id}', [App\Http\Controllers\Admin\AdminCategoryController::class, 'destroy'])->name('categories.destroy');
    
    // Extra: Trashed and Restore
    Route::get('categories/trashed/list', [App\Http\Controllers\Admin\AdminCategoryController::class, 'trashed'])->name('categories.trashed');
    Route::patch('categories/{id}/restore', [App\Http\Controllers\Admin\AdminCategoryController::class, 'restore'])->name('categories.restore');

    /** -------------------------
     * District Management Routes
     * -------------------------- */
    Route::get('districts', [App\Http\Controllers\Admin\AdminDistrictController::class, 'index'])->name('districts.index');
    Route::get('districts/create', [App\Http\Controllers\Admin\AdminDistrictController::class, 'create'])->name('districts.create');
    Route::post('districts', [App\Http\Controllers\Admin\AdminDistrictController::class, 'store'])->name('districts.store');
    Route::get('districts/{district}', [App\Http\Controllers\Admin\AdminDistrictController::class, 'show'])->name('districts.show');
    Route::get('districts/{district}/edit', [App\Http\Controllers\Admin\AdminDistrictController::class, 'edit'])->name('districts.edit');
    Route::put('districts/{district}', [App\Http\Controllers\Admin\AdminDistrictController::class, 'update'])->name('districts.update');
    Route::delete('districts/{district}', [App\Http\Controllers\Admin\AdminDistrictController::class, 'destroy'])->name('districts.destroy');
    
    // Extra: Custom actions
    Route::patch('districts/{district}/toggle-status', [App\Http\Controllers\Admin\AdminDistrictController::class, 'toggleStatus'])->name('districts.toggle-status');
    Route::post('districts/bulk-update-fees', [App\Http\Controllers\Admin\AdminDistrictController::class, 'bulkUpdateFees'])->name('districts.bulk-update-fees');

});