<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;  //Built In Notification



//Landing Page
Route::get('/', function () {
    return view('welcome');
});



//-------------------------------------------------Customer Route-------------------------------------------------// 

use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Customer\CustomerProfileController;
use App\Http\Controllers\Customer\CustomerPasswordController;
use App\Http\Controllers\Customer\CustomerSavedAddressController;
use App\Http\Controllers\Customer\CustomerProductController;
use App\Http\Controllers\Customer\CustomerShoppingCartController;
use App\Http\Controllers\Customer\CustomerCheckoutController;
use App\Http\Controllers\Customer\CustomerPaymentController;
use App\Http\Controllers\Customer\CustomerOrderFulfillmentController;
use App\Http\Controllers\Customer\CustomerOrderController;






//Customer Authentication Routes (Login & Register)
Route::middleware('guest')->group(function () {
    Route::get('/customer/login', [CustomerAuthController::class, 'showLoginForm'])->name('customer.login');
    Route::post('/customer/login', [CustomerAuthController::class, 'login']);

    Route::get('/register', [CustomerAuthController::class, 'showRegisterForm'])->name('customer.register');
    Route::post('/register', [CustomerAuthController::class, 'register']);
});

//Customer Logout (requires auth)
Route::post('/customer/logout', [CustomerAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('customer.logout');

//Customer Protected Routes (requires auth & customer role)
Route::middleware(['auth', 'role:customer'])->name('customer.')->group(function () {

    // Dashboard
    Route::get('/home', [CustomerDashboardController::class, 'dashboard'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [CustomerProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [CustomerProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [CustomerProfileController::class, 'update'])->name('profile.update');

    // Password Management
    Route::get('/profile/password/edit', [CustomerPasswordController::class, 'edit'])->name('password.edit');
    Route::put('/profile/password', [CustomerPasswordController::class, 'update'])->name('password.update');

     //Saved Addresses
    Route::prefix('saved_addresses')->name('saved_addresses.')->group(function () {
        Route::get('/', [CustomerSavedAddressController::class, 'index'])->name('index');
        Route::get('/create', [CustomerSavedAddressController::class, 'create'])->name('create');
        Route::post('/', [CustomerSavedAddressController::class, 'store'])->name('store');
        Route::get('/{saved_address}/edit', [CustomerSavedAddressController::class, 'edit'])->name('edit');
        Route::put('/{saved_address}', [CustomerSavedAddressController::class, 'update'])->name('update');
        Route::delete('/{saved_address}', [CustomerSavedAddressController::class, 'destroy'])->name('destroy');
    });
});

// Product browsing routes
Route::get('/products', [CustomerProductController::class, 'index'])->name('products.index');
Route::get('/products/search', [CustomerProductController::class, 'search'])->name('products.search');
Route::get('/products/{id}', [CustomerProductController::class, 'show'])->name('products.show');
Route::get('/products/category/{categoryId}', [CustomerProductController::class, 'category'])->name('products.category');
Route::get('/products/vendor/{vendorId}', [CustomerProductController::class, 'vendor'])->name('products.vendor');


// Shopping Cart Routes (for authenticated customers)
Route::middleware(['auth'])->group(function () {
    // Cart display and management
    Route::get('/cart', [CustomerShoppingCartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CustomerShoppingCartController::class, 'store'])->name('cart.store');

Route::put('/cart/{cartItem}', [CustomerShoppingCartController::class, 'update'])
    ->name('cart.update')
    ->where('cartItem', '[0-9]+'); // Ensure only numeric IDs

Route::delete('/cart/{cartItem}', [CustomerShoppingCartController::class, 'destroy'])
    ->name('cart.destroy')
    ->where('cartItem', '[0-9]+');
    
    Route::delete('/cart', [CustomerShoppingCartController::class, 'clear'])->name('cart.clear');
    
    // AJAX route for cart count
    Route::get('/cart/count', [CustomerShoppingCartController::class, 'getCartCount'])->name('cart.count');
});

//Checkout Route
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CustomerCheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/delivery-fee', [CustomerCheckoutController::class, 'getDeliveryFee'])->name('checkout.delivery-fee');
    Route::post('/checkout/process', [CustomerCheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/confirmation', [CustomerCheckoutController::class, 'confirmation'])->name('checkout.confirmation'); 
    Route::get('/checkout/payment-confirmation', [CustomerPaymentController::class, 'paymentConfirmation'])->name('checkout.payment-confirmation');
});

//Payment Route
Route::middleware(['auth'])->group(function () {
    Route::match(['GET', 'POST'], '/payment/confirmation', [CustomerPaymentController::class, 'paymentConfirmation'])->name('payment.confirmation');
    Route::post('/payment/process-online', [CustomerPaymentController::class, 'processOnlinePayment'])->name('payment.process-online');
    Route::post('/payment/process-cod', [CustomerPaymentController::class, 'processCOD'])->name('payment.process-cod');
});

// PayMongo Callback Routes (no auth middleware needed for callbacks)
Route::prefix('payment')->name('payment.')->group(function () {
    
    // PayMongo success callback
    Route::get('/success', [CustomerPaymentController::class, 'paymentSuccess'])
        ->name('success');
    
    // PayMongo failure/cancel callback  
    Route::get('/failed', [CustomerPaymentController::class, 'paymentFailed'])
        ->name('failed');
    
    // Alternative naming for callbacks
    Route::get('/callback/success', [CustomerPaymentController::class, 'paymentSuccess'])
        ->name('callback.success');
    
    Route::get('/callback/failed', [CustomerPaymentController::class, 'paymentFailed'])
        ->name('callback.failed');
});

Route::middleware(['auth'])->group(function () {
    // Customer Orders Routes
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    });
});

// Order Fulfillment Routes
Route::middleware(['auth'])->group(function () {
    
    // Customer Order Status Routes
    Route::get('/orders/{order}/status', [CustomerOrderFulfillmentController::class, 'showOrderStatus'])
        ->name('orders.status')
        ->middleware('can:view,order'); // Ensure customers can only view their own orders

    // AJAX endpoint for real-time order status updates
    Route::get('/orders/{order}/status-update', [CustomerOrderFulfillmentController::class, 'getOrderStatusUpdate'])
        ->name('orders.status.update')
        ->middleware('can:view,order');

    // COD Order Processing (called after order creation)
    Route::post('/orders/{order}/process-cod', [CustomerOrderFulfillmentController::class, 'processCodOrder'])
        ->name('orders.process.cod')
        ->middleware('can:update,order');

    // Vendor Routes - Mark items as ready for pickup
    Route::patch('/orders/items/ready', [CustomerOrderFulfillmentController::class, 'handleVendorItemReady'])
        ->name('orders.items.ready')
        ->middleware('role:vendor'); // Assuming you have role middleware
});

// Webhook Routes (No authentication - external services)
Route::post('/webhooks/paymongo/payment-verified', [CustomerOrderFulfillmentController::class, 'handlePaymentWebhook'])
    ->name('webhooks.paymongo.payment');

// Notification Routes (following your reference implementation)
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');
    
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.mark-as-read');
    
    Route::patch('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.mark-all-as-read');
    
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])
        ->name('notifications.unread-count');
});

// Debug route for testing PayMongo configuration (remove in production)
Route::get('/test/paymongo-config', function() {
    $config = [
        'secret_key' => config('services.paymongo.secret_key') ? 'Configured' : 'Not configured',
        'public_key' => config('services.paymongo.public_key') ? 'Configured' : 'Not configured',
        'webhook_secret' => config('services.paymongo.webhook_secret') ? 'Configured' : 'Not configured',
    ];
    
    return response()->json([
        'message' => 'PayMongo Configuration Status',
        'config' => $config,
        'timestamp' => now()
    ]);
})->name('test.paymongo.config');

    


//-------------------------------------------------Vendor Route-------------------------------------------------// 

use App\Http\Controllers\Auth\VendorAuthController;
use App\Http\Controllers\Vendor\VendorDashboardController;
use App\Http\Controllers\Vendor\VendorProfileController;
use App\Http\Controllers\Vendor\VendorProductController;
use App\Http\Controllers\Vendor\VendorEarningController;
use App\Http\Controllers\Vendor\VendorRatingController;

//Vendor Registration & Login Routes
Route::middleware('guest')->group(function () {
    // Vendor Application (Registration)
    Route::get('/vendor-application', [VendorAuthController::class, 'create'])->name('vendor-applications.create');
    Route::post('/vendor-application', [VendorAuthController::class, 'store'])->name('vendor-applications.store');

    // Vendor Login
   Route::get('/vendor/login', [VendorAuthController::class, 'showLoginForm'])->name('vendor.login.form');
Route::post('/vendor/login', [VendorAuthController::class, 'login'])->name('vendor.login');
});

//Vendor Protected Routes (requires auth & vendor role)
Route::middleware(['auth', 'role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {

    // Dashboard
    Route::get('dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');
    Route::post('/vendor/toggle-accepting', [VendorDashboardController::class, 'toggleAcceptingOrders'])->name('toggle.accepting');


    // Logout
    Route::post('logout', [VendorAuthController::class, 'logout'])->name('logout');

    //Profile Management
    Route::get('profile', [VendorProfileController::class, 'index'])->name('profile.index');
    Route::get('profile/edit', [VendorProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [VendorProfileController::class, 'update'])->name('profile.update');

    // Shop Ratings (optional grouping if you want to split later)
    Route::get('shop/ratings', [VendorProfileController::class, 'ratings'])->name('profile.ratings');

    // Product Management
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [VendorProductController::class, 'index'])->name('index');
        Route::get('/create', [VendorProductController::class, 'create'])->name('create');
        Route::post('/', [VendorProductController::class, 'store'])->name('store');
        Route::get('/{product}', [VendorProductController::class, 'show'])->name('show');
        Route::get('/{product}/edit', [VendorProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [VendorProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [VendorProductController::class, 'destroy'])->name('destroy');

        // Toggle product availability
        Route::patch('/{product}/toggle-availability', [VendorProductController::class, 'toggleAvailability'])->name('toggle-availability');
    });

    // Earnings and Payouts
    Route::prefix('earnings')->name('earnings.')->group(function () {
        Route::get('/', [VendorEarningController::class, 'index'])->name('index');
        Route::get('/payout/{id}', [VendorEarningController::class, 'showPayout'])->name('payout.details');
    });

   // Ratings
    Route::get('ratings', [VendorRatingController::class, 'index'])->name('ratings.index');
});






//-------------------------------------------------Rider Route-------------------------------------------------// 


use App\Http\Controllers\Auth\RiderAuthController;
use App\Http\Controllers\Rider\RiderDashboardController;
use App\Http\Controllers\Rider\RiderProfileController;
use App\Http\Controllers\Rider\RiderEarningsController;
use App\Http\Controllers\Rider\RiderRatingController;

// Rider Registration & Login Routes
Route::middleware('guest')->group(function () {
    // Rider Application (Registration)
    Route::get('/rider-application', [RiderAuthController::class, 'create'])->name('rider-applications.create');
    Route::post('/rider-application', [RiderAuthController::class, 'store'])->name('rider-applications.store');

    // Rider Login
    Route::get('/rider/login', [RiderAuthController::class, 'showLoginForm'])->name('rider.login');
    Route::post('/rider/login', [RiderAuthController::class, 'login']);
});

// Rider Logout (requires auth)
Route::post('/rider/logout', [RiderAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('rider.logout');

// Rider Protected Routes (requires auth & rider role)
Route::middleware(['auth', 'role:rider'])->prefix('rider')->name('rider.')->group(function () {

    // Dashboard
    Route::get('dashboard', [RiderDashboardController::class, 'dashboard'])->name('dashboard');

    // Rider Profile
    Route::get('profile', [RiderProfileController::class, 'show'])->name('profile.show');
    Route::get('profile/edit', [RiderProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [RiderProfileController::class, 'update'])->name('profile.update');

    // Rider Earnings, Payouts & Ratings
    Route::get('earnings', [RiderEarningsController::class, 'earnings'])->name('earnings');
    Route::get('payouts', [RiderEarningsController::class, 'payouts'])->name('payouts');
    Route::get('ratings', [RiderRatingController::class, 'ratings'])->name('ratings');
});









//-------------------------------------------------Admin Route-------------------------------------------------// 

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminVendorController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminDistrictController;
use App\Http\Controllers\Admin\AdminRiderApplicationController;
use App\Http\Controllers\Admin\AdminVendorApplicationController;

//Admin Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login']);
});

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('admin.logout');

//Admin Protected Routes (Requires auth & admin role)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');

    //User Management
    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    //Rider Applications
    Route::prefix('rider-applications')->name('rider_applications.')->group(function () {
        Route::get('/', [AdminRiderApplicationController::class, 'index'])->name('index');
        Route::get('{rider_application}', [AdminRiderApplicationController::class, 'show'])->name('show');
        Route::delete('{rider_application}', [AdminRiderApplicationController::class, 'destroy'])->name('destroy');
        Route::get('{id}/create-rider', [AdminRiderApplicationController::class, 'createRiderFromApplication'])->name('createRider');
        Route::post('{id}/store-rider', [AdminRiderApplicationController::class, 'storeRiderFromApplication'])->name('storeRider');
    });

    // Vendor Applications
    Route::prefix('vendor-applications')->name('vendor_applications.')->group(function () {
        Route::get('/', [AdminVendorApplicationController::class, 'index'])->name('index');
        Route::get('{vendor_application}', [AdminVendorApplicationController::class, 'show'])->name('show');
        Route::delete('{vendor_application}', [AdminVendorApplicationController::class, 'destroy'])->name('destroy');
        Route::get('{id}/create-vendor', [AdminVendorApplicationController::class, 'createVendorFromApplication'])->name('createVendor');
        Route::post('{id}/store-vendor', [AdminVendorApplicationController::class, 'storeVendorFromApplication'])->name('storeVendor');
    });

    // Orders Management
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('orders/{id}/assign-rider', [AdminOrderController::class, 'assignRider'])->name('orders.assignRider');

    // Vendors Management
    Route::get('vendors', [AdminVendorController::class, 'index'])->name('vendors.index');
    Route::post('vendors/{id}/toggle', [AdminVendorController::class, 'toggleStatus'])->name('vendors.toggle-status');

   // Products Management
    Route::get('products', [AdminProductController::class, 'index'])->name('products.index');
    Route::post('products/{id}/toggle', [AdminProductController::class, 'toggleAvailability'])->name('products.toggle');
    Route::delete('products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    // Categories Management
    Route::get('categories', [AdminCategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/create', [AdminCategoryController::class, 'create'])->name('categories.create');
    Route::post('categories', [AdminCategoryController::class, 'store'])->name('categories.store');
    Route::get('categories/{category}', [AdminCategoryController::class, 'show'])->name('categories.show');
    Route::get('categories/{category}/edit', [AdminCategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{category}', [AdminCategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [AdminCategoryController::class, 'destroy'])->name('categories.destroy');

    // Trashed & Restore
    Route::get('categories/trashed/list', [AdminCategoryController::class, 'trashed'])->name('categories.trashed');
    Route::patch('categories/{id}/restore', [AdminCategoryController::class, 'restore'])->name('categories.restore');

    // Districts Management
    Route::get('districts', [AdminDistrictController::class, 'index'])->name('districts.index');
    Route::get('districts/create', [AdminDistrictController::class, 'create'])->name('districts.create');
    Route::post('districts', [AdminDistrictController::class, 'store'])->name('districts.store');
    Route::get('districts/{district}', [AdminDistrictController::class, 'show'])->name('districts.show');
    Route::get('districts/{district}/edit', [AdminDistrictController::class, 'edit'])->name('districts.edit');
    Route::put('districts/{district}', [AdminDistrictController::class, 'update'])->name('districts.update');
    Route::delete('districts/{district}', [AdminDistrictController::class, 'destroy'])->name('districts.destroy');

    // Extra Actions
    Route::patch('districts/{district}/toggle-status', [AdminDistrictController::class, 'toggleStatus'])->name('districts.toggle-status');
    Route::post('districts/bulk-update-fees', [AdminDistrictController::class, 'bulkUpdateFees'])->name('districts.bulk-update-fees');
});
