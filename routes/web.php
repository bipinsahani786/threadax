<?php

use Illuminate\Support\Facades\Route;

// ─── Auth Controllers ──────────────────────────────────────────────────────
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\SocialController;

// ─── Frontend Controllers ──────────────────────────────────────────────────
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\AccountController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\NewsletterController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\BlogController as FrontendBlogController;
use App\Http\Controllers\Frontend\PageController as FrontendPageController;

// ─── Admin Controllers ─────────────────────────────────────────────────────
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Webhook\RazorpayWebhookController;

// ══════════════════════════════════════════════════════════════════════════════
// FRONTEND PUBLIC ROUTES
// ══════════════════════════════════════════════════════════════════════════════
Route::name('frontend.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/shop', [ProductController::class, 'index'])->name('products.index');
    Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::post('/newsletter', [NewsletterController::class, 'store'])->name('newsletter.store');

    // Blog
    Route::get('/blog', [FrontendBlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}', [FrontendBlogController::class, 'show'])->name('blog.show');

    // Static Pages (About Us, Privacy, Terms etc.)
    Route::get('/page/{slug}', [FrontendPageController::class, 'show'])->name('page.show');
    Route::post('/contact-submit', [\App\Http\Controllers\Frontend\ContactController::class, 'store'])->name('contact.submit');

    // Product Reviews (auth required)
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])
        ->name('reviews.store')
        ->middleware('auth');

    // Cart API (AlpineJS)
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/data', [CartController::class, 'index'])->name('data');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::post('/update', [CartController::class, 'update'])->name('update');
        Route::post('/remove', [CartController::class, 'remove'])->name('remove');
        Route::post('/{id}/move-to-wishlist', [CartController::class, 'moveToWishlist'])->name('moveToWishlist');
        Route::post('/{id}/move-from-wishlist', [CartController::class, 'moveFromWishlist'])->name('moveFromWishlist');
    });
});

// ══════════════════════════════════════════════════════════════════════════════
// AUTH ROUTES
// ══════════════════════════════════════════════════════════════════════════════
Route::prefix('/auth')->name('auth.')->group(function () {

    // Guest-only routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'show'])->name('login');
        Route::post('/otp/send', [OtpController::class, 'send'])
            ->middleware('throttle:5,1')
            ->name('otp.send');
        Route::get('/otp/verify', [OtpController::class, 'verifyForm'])->name('otp.verify.form');
        Route::post('/otp/verify', [OtpController::class, 'verify'])
            ->middleware('throttle:10,1')
            ->name('otp.verify');
        Route::get('/google', [SocialController::class, 'redirectToGoogle'])->name('google.redirect');
        Route::get('/google/callback', [SocialController::class, 'handleGoogleCallback'])->name('google.callback');
    });

    // Auth required
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    });
});

// ══════════════════════════════════════════════════════════════════════════════
// FRONTEND AUTH-REQUIRED ROUTES
// ══════════════════════════════════════════════════════════════════════════════
Route::middleware('auth')->group(function () {

    // ── Checkout ────────────────────────────────────────────────────────────
    Route::prefix('checkout')->name('frontend.checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/process', [CheckoutController::class, 'process'])->name('process');
        Route::post('/callback', [CheckoutController::class, 'callback'])->name('callback');
        Route::get('/success/{orderId}', [CheckoutController::class, 'success'])->name('success');

        // Coupon (correctly inside checkout prefix)
        Route::post('/coupon/apply', [CheckoutController::class, 'applyCoupon'])->name('coupon.apply');
        Route::post('/coupon/remove', [CheckoutController::class, 'removeCoupon'])->name('coupon.remove');
    });

    // ── Account ─────────────────────────────────────────────────────────────
    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('dashboard');
        
        Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [AccountController::class, 'showOrder'])->name('orders.show');
        Route::get('/orders/{id}/invoice', [AccountController::class, 'downloadInvoice'])->name('orders.invoice');
        
        Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
        Route::post('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
        
        Route::get('/addresses', [AccountController::class, 'addresses'])->name('addresses');
        Route::post('/addresses', [AccountController::class, 'storeAddress'])->name('addresses.store');
        Route::delete('/addresses/{id}', [AccountController::class, 'destroyAddress'])->name('addresses.destroy');
        
        Route::get('/reviews', [AccountController::class, 'reviews'])->name('reviews');
        
        // Notifications
        Route::get('/notifications', [AccountController::class, 'notifications'])->name('notifications.index');
        Route::post('/notifications/mark-all-read', [AccountController::class, 'markAllRead'])->name('notifications.markAllRead');

        // Wishlist
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
        Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    });
});

// ══════════════════════════════════════════════════════════════════════════════
// WEBHOOKS (excluded from CSRF in bootstrap/app.php)
// ══════════════════════════════════════════════════════════════════════════════
Route::post('/webhooks/razorpay', [RazorpayWebhookController::class, 'handle'])
    ->name('webhooks.razorpay');

// ══════════════════════════════════════════════════════════════════════════════
// ADMIN ROUTES
// ══════════════════════════════════════════════════════════════════════════════
Route::prefix('/admin')->name('admin.')->group(function () {

    // Admin guest routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])
            ->middleware('throttle:5,1')
            ->name('login.post');
    });

    // Admin authenticated routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Categories & Products
        Route::resource('/categories', CategoryController::class)->except(['show']);
        Route::resource('/products', AdminProductController::class)->except(['show']);

        // Product Variants & Images
        Route::get('/products/{product}/variants', [ProductVariantController::class, 'index'])->name('products.variants');
        Route::post('/products/{product}/variants', [ProductVariantController::class, 'storeVariants'])->name('products.variants.store');
        Route::post('/products/{product}/images', [ProductVariantController::class, 'storeImages'])->name('products.images.store');
        Route::post('/images/{image}/primary', [ProductVariantController::class, 'setPrimaryImage'])->name('images.primary');
        Route::delete('/images/{image}', [ProductVariantController::class, 'deleteImage'])->name('images.destroy');

        // Orders
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status.update');
        Route::get('/orders/{id}/invoice', [AdminOrderController::class, 'downloadInvoice'])->name('orders.invoice.download');

        // Coupons
        Route::resource('/coupons', CouponController::class)->except(['show']);

        // Reviews
        Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{review}/status', [AdminReviewController::class, 'updateStatus'])->name('reviews.status.update');

        // Banners
        Route::resource('/banners', BannerController::class)->except(['show']);

        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'exportCsv'])->name('reports.export');

        // Customers
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');

        // Testimonials
        Route::resource('/testimonials', TestimonialController::class)->except(['show']);

        // FAQs
        Route::resource('/faqs', FaqController::class)->except(['show']);

        // Blogs & Pages
        Route::resource('/blogs', AdminBlogController::class);
        Route::resource('/pages', AdminPageController::class)->except(['show']);

        // Notifications
        Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'create'])->name('notifications.create');
        Route::post('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'store'])->name('notifications.store');

        // Static Pages
        Route::resource('static-pages', \App\Http\Controllers\Admin\StaticPageController::class)->except(['create', 'store', 'edit']);

        // Leads
        Route::resource('/leads', \App\Http\Controllers\Admin\LeadController::class)->except(['create', 'store', 'edit']);

        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});
