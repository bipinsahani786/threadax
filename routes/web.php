<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;

// ─── Frontend Public Routes ────────────────────────────────────────────────
Route::name('frontend.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/shop', [\App\Http\Controllers\Frontend\ProductController::class, 'index'])->name('products.index');
    Route::get('/product/{slug}', [\App\Http\Controllers\Frontend\ProductController::class, 'show'])->name('products.show');

    // Cart API routes for AlpineJS
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/data', [\App\Http\Controllers\Frontend\CartController::class, 'index'])->name('data');
        Route::post('/add', [\App\Http\Controllers\Frontend\CartController::class, 'add'])->name('add');
        Route::post('/update', [\App\Http\Controllers\Frontend\CartController::class, 'update'])->name('update');
        Route::post('/remove', [\App\Http\Controllers\Frontend\CartController::class, 'remove'])->name('remove');
    });
});

// ─── Auth Routes ──────────────────────────────────────────────────────────
Route::prefix('/auth')->name('auth.')->group(function () {

    // Guest-only routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'show'])->name('login');
        Route::post('/otp/send', [OtpController::class, 'send'])->name('otp.send');
        Route::get('/otp/verify', [OtpController::class, 'verifyForm'])->name('otp.verify.form');
        Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');
        Route::get('/google', [SocialController::class, 'redirectToGoogle'])->name('google.redirect');
        Route::get('/google/callback', [SocialController::class, 'handleGoogleCallback'])->name('google.callback');
    });

    // Auth required
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    });
});

// ─── Frontend Auth Required Routes ────────────────────────────────────────
Route::middleware('auth')->group(function () {
    
    // Checkout Routes
    Route::prefix('checkout')->name('frontend.checkout.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Frontend\CheckoutController::class, 'index'])->name('index');
        Route::post('/process', [\App\Http\Controllers\Frontend\CheckoutController::class, 'process'])->name('process');
        Route::post('/callback', [\App\Http\Controllers\Frontend\CheckoutController::class, 'callback'])->name('callback');
        Route::get('/success/{orderId}', [\App\Http\Controllers\Frontend\CheckoutController::class, 'success'])->name('success');
    });

    // Account Routes
    Route::prefix('/account')->name('account.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Frontend\AccountController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders', [\App\Http\Controllers\Frontend\AccountController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [\App\Http\Controllers\Frontend\AccountController::class, 'showOrder'])->name('orders.show');
        Route::get('/profile', [\App\Http\Controllers\Frontend\AccountController::class, 'profile'])->name('profile');
        Route::post('/profile', [\App\Http\Controllers\Frontend\AccountController::class, 'updateProfile'])->name('profile.update');
        Route::get('/addresses', [\App\Http\Controllers\Frontend\AccountController::class, 'addresses'])->name('addresses');
        Route::delete('/addresses/{id}', [\App\Http\Controllers\Frontend\AccountController::class, 'destroyAddress'])->name('addresses.destroy');
        Route::get('/wishlist', [\App\Http\Controllers\Frontend\WishlistController::class, 'index'])->name('wishlist');
        Route::post('/wishlist/toggle', [\App\Http\Controllers\Frontend\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    });
});

// ─── Admin Routes ─────────────────────────────────────────────────────────
Route::prefix('/admin')->name('admin.')->group(function () {

    // Admin guest routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    });

    // Admin authenticated routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('/categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['show']);
        Route::resource('/products', \App\Http\Controllers\Admin\ProductController::class)->except(['show']);
        
        // Product Variants & Images
        Route::get('/products/{product}/variants', [\App\Http\Controllers\Admin\ProductVariantController::class, 'index'])->name('products.variants');
        Route::post('/products/{product}/variants', [\App\Http\Controllers\Admin\ProductVariantController::class, 'storeVariants'])->name('products.variants.store');
        Route::post('/products/{product}/images', [\App\Http\Controllers\Admin\ProductVariantController::class, 'storeImages'])->name('products.images.store');
        Route::post('/images/{image}/primary', [\App\Http\Controllers\Admin\ProductVariantController::class, 'setPrimaryImage'])->name('images.primary');
        Route::delete('/images/{image}', [\App\Http\Controllers\Admin\ProductVariantController::class, 'deleteImage'])->name('images.destroy');
        
        // Orders
        Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status.update');
        
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});
