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
use App\Http\Controllers\Frontend\TrackingController;
use App\Http\Controllers\Frontend\SitemapController;
use App\Http\Controllers\Frontend\ReturnController as FrontendReturnController;
use App\Http\Controllers\Frontend\BlogController as FrontendBlogController;
use App\Http\Controllers\Frontend\PageController as FrontendPageController;
use App\Http\Controllers\Webhook\ShiprocketWebhookController;

// ─── Admin Controllers ─────────────────────────────────────────────────────
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReturnController as AdminReturnController;
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
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\Admin\SystemLogController;
use App\Http\Controllers\Webhook\RazorpayWebhookController;

// ══════════════════════════════════════════════════════════════════════════════
// FRONTEND PUBLIC ROUTES
// ══════════════════════════════════════════════════════════════════════════════
Route::name('frontend.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/shop', [ProductController::class, 'index'])->name('products.index');
    Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/search', [SearchController::class, 'index'])->name('search');
    Route::get('/track-order', [TrackingController::class, 'index'])->name('tracking');
    Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
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
        Route::post('/cancel', [CheckoutController::class, 'cancel'])->name('cancel');
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
        Route::get('/orders/{id}/return', [FrontendReturnController::class, 'create'])->name('orders.return.create');
        Route::post('/orders/{id}/return', [FrontendReturnController::class, 'store'])->name('orders.return.store');
        
        Route::get('/returns', [FrontendReturnController::class, 'index'])->name('returns');
        Route::get('/returns/{id}', [FrontendReturnController::class, 'show'])->name('returns.show');
        Route::get('/transactions', [AccountController::class, 'transactions'])->name('transactions');
        
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
// CUSTOMER IMPERSONATION & PUSH TOKENS
// ══════════════════════════════════════════════════════════════════════════════
Route::get('/impersonate/leave', [\App\Http\Controllers\Admin\CustomerController::class, 'leaveImpersonation'])
    ->name('impersonate.leave');
Route::post('/push-tokens/save', [\App\Http\Controllers\Api\PushTokenController::class, 'store'])
    ->name('push-tokens.save');
Route::post('/push-tokens/remove', [\App\Http\Controllers\Api\PushTokenController::class, 'destroy'])
    ->name('push-tokens.remove');

// ══════════════════════════════════════════════════════════════════════════════
// WEBHOOKS (excluded from CSRF in bootstrap/app.php)
// ══════════════════════════════════════════════════════════════════════════════
Route::post('/webhooks/razorpay', [RazorpayWebhookController::class, 'handle'])
    ->name('webhooks.razorpay');
Route::post('/webhooks/shiprocket', [ShiprocketWebhookController::class, 'handle'])
    ->name('webhooks.shiprocket');

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
        Route::post('/notifications/mark-read', [DashboardController::class, 'markNotificationsRead'])->name('notifications.markRead');

        // Categories & Products
        Route::resource('/categories', CategoryController::class)->except(['show']);
        // AI Content & Photoshoot Generator (Gemini)
        Route::post('/products/ai-generate-content', [AdminProductController::class, 'generateAiContent'])->name('products.ai.generate-content');
        Route::post('/products/ai-generate-image', [AdminProductController::class, 'generateAiImage'])->name('products.ai.generate-image');
        Route::post('/products/save-gemini-key', [AdminProductController::class, 'saveGeminiKey'])->name('products.ai.save-key');

        Route::post('/products/{product}/duplicate', [AdminProductController::class, 'duplicate'])->name('products.duplicate');
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
        Route::post('/orders/{id}/refund', [AdminOrderController::class, 'issueRefund'])->name('orders.refund');
        Route::post('/orders/{id}/tracking', [AdminOrderController::class, 'addTracking'])->name('orders.tracking.store');
        Route::post('/orders/{id}/shiprocket-push', [AdminOrderController::class, 'pushToShiprocket'])->name('orders.shiprocket.push');
        Route::post('/orders/{id}/shiprocket-awb', [AdminOrderController::class, 'generateShiprocketAwb'])->name('orders.shiprocket.awb');
        Route::post('/orders/{id}/shiprocket-sync', [AdminOrderController::class, 'syncShiprocketTracking'])->name('orders.shiprocket.sync');
        Route::get('/orders/{id}/shipping-label', [AdminOrderController::class, 'shippingLabel'])->name('orders.shipping.label');
        Route::get('/orders/{id}/invoice', [AdminOrderController::class, 'downloadInvoice'])->name('orders.invoice.download');

        // Returns & Exchanges
        Route::get('/returns', [AdminReturnController::class, 'index'])->name('returns.index');
        Route::get('/returns/{id}', [AdminReturnController::class, 'show'])->name('returns.show');
        Route::post('/returns/{id}/status', [AdminReturnController::class, 'updateStatus'])->name('returns.status.update');
        Route::post('/returns/{id}/refund', [AdminReturnController::class, 'processRefund'])->name('returns.refund');
        Route::post('/returns/{id}/restock', [AdminReturnController::class, 'restockItems'])->name('returns.restock');

        // Transactions & Payment Gateway Ledger
        Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{id}', [AdminTransactionController::class, 'show'])->name('transactions.show');

        // Coupons
        Route::resource('/coupons', CouponController::class)->except(['show']);

        // Reviews
        Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{review}/status', [AdminReviewController::class, 'updateStatus'])->name('reviews.status.update');
        Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

        // Banners
        Route::resource('/banners', BannerController::class)->except(['show']);

        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

        // System Logs & Optimizer
        Route::get('/logs', [SystemLogController::class, 'index'])->name('logs.index');
        Route::post('/logs/clear', [SystemLogController::class, 'clear'])->name('logs.clear');
        Route::get('/logs/download', [SystemLogController::class, 'download'])->name('logs.download');
        Route::post('/logs/optimize', [SystemLogController::class, 'optimize'])->name('logs.optimize');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'exportCsv'])->name('reports.export');

        // Customers & Impersonation
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::post('/customers/{user}/impersonate', [CustomerController::class, 'impersonate'])->name('customers.impersonate');
        Route::post('/customers/{user}/send-email', [CustomerController::class, 'sendEmail'])->name('customers.email.send');

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
        Route::post('/notifications/test-device/{deviceToken}', [\App\Http\Controllers\Admin\NotificationController::class, 'testDevice'])->name('notifications.test-device');
        Route::delete('/notifications/device/{deviceToken}', [\App\Http\Controllers\Admin\NotificationController::class, 'destroyDevice'])->name('notifications.device.destroy');

        // Leads
        Route::resource('/leads', \App\Http\Controllers\Admin\LeadController::class)->except(['create', 'store', 'edit']);
        Route::post('/leads/{lead}/reply', [\App\Http\Controllers\Admin\LeadController::class, 'sendReply'])->name('leads.reply');

        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});
