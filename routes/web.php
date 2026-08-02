<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
Route::post('/chatbot/message', [ChatbotController::class, 'sendMessage'])->name('chatbot.message');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Checkout routes
Route::middleware('auth')->prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CartController::class, 'checkout'])->name('index');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
});

Route::middleware('auth')->prefix('order')->name('order.')->group(function () {
    Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
    Route::get('/track', [OrderController::class, 'track'])->name('track');
    Route::post('/search', [OrderController::class, 'search'])->name('search');
    Route::get('/history', [OrderController::class, 'myOrders'])->name('history');
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('my-orders');
    Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    Route::get('/{order}/confirm-whatsapp', [OrderController::class, 'confirmViaWhatsapp'])->name('confirm-whatsapp');
    Route::get('/{order}', [OrderController::class, 'show'])->name('detail');
});

Route::middleware('auth')->post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');

Route::middleware('auth')->get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
Route::middleware('auth')->put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

// Admin routes
Route::get('/admin/login', [App\Http\Controllers\AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\AdminAuthController::class, 'authenticate'])->name('admin.login.submit');
Route::post('/admin/logout', [App\Http\Controllers\AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', [App\Http\Controllers\AdminDashboardController::class, 'settings'])->name('settings.index');
    Route::put('/settings/operational-hours', [App\Http\Controllers\AdminDashboardController::class, 'updateOperationalHours'])->name('settings.operational-hours.update');
    Route::get('/sales/export/pdf', [App\Http\Controllers\AdminDashboardController::class, 'exportPdf'])->name('sales.export.pdf');
    Route::get('/sales/export/excel', [App\Http\Controllers\AdminDashboardController::class, 'exportExcel'])->name('sales.export.excel');

    Route::resource('products', App\Http\Controllers\AdminProductController::class);
    Route::resource('categories', App\Http\Controllers\AdminCategoryController::class);
    
    // Admin Orders routes
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [App\Http\Controllers\AdminOrderController::class, 'show'])->name('show');
        Route::get('/{order}/edit', [App\Http\Controllers\AdminOrderController::class, 'edit'])->name('edit');
        Route::put('/{order}', [App\Http\Controllers\AdminOrderController::class, 'update'])->name('update');
        Route::post('/{order}/confirm-payment', [App\Http\Controllers\AdminOrderController::class, 'confirmPayment'])->name('confirm-payment');
    });
});
