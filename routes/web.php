<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\Admin\DashboardController;


Route::get('/', function () {
    return view('welcome');
})->name('home');

// --- Auth ---
Route::prefix('/auth')->name('auth.')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login')->middleware('guest');
    Route::post('/otp/send', [OtpController::class, 'send'])->name('otp.send')->middleware('guest');
    Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify')->middleware('guest');

    Route::get('/google', [SocialController::class, 'redirect'])->name('google.redirect')->middleware('guest');
    Route::get('/google/callback', [SocialController::class, 'callback'])->name('google.callback')->middleware('guest');

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
});

// --- Frontend (Auth Required) ---
Route::middleware(['auth'])->prefix('/account')->name('account.')->group(function () {
    Route::get('/dashboard', function () {
        return "User Dashboard - Welcome " . auth()->user()->name;
    })->name('dashboard');
});

// --- Admin ---
Route::prefix('/admin')->name('admin.')->group(function () {
    // Admin Guest Routes (Login)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'login'])->name('login.post');
    });

    // Admin Authenticated Routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');
    });
});
