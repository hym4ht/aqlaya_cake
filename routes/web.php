<?php

use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\CustomerApprovalController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MidtransWebhookController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog', [HomeController::class, 'index'])->name('catalog');
Route::get('/products/{product:slug}', [HomeController::class, 'show'])->name('products.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::post('/midtrans/webhook', MidtransWebhookController::class)
    ->withoutMiddleware([ValidateCsrfToken::class])
    ->name('midtrans.webhook');

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/products/{product}/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{itemId}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{itemId}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/simulate-payment', [CustomerOrderController::class, 'simulatePayment'])->name('orders.simulate-payment');
    Route::post('/orders/{order}/reviews/{product}', [CustomerOrderController::class, 'storeReview'])->name('orders.reviews.store');
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::patch('/customers/{user}/decision', CustomerApprovalController::class)->name('customers.decide');
        Route::resource('products', AdminProductController::class)->except('show');
        Route::patch('/products/{product}/toggle-best-seller', [AdminProductController::class, 'toggleBestSeller'])->name('products.toggle-best-seller');
        Route::resource('banners', AdminBannerController::class)->except('show');
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/decision', [AdminOrderController::class, 'decide'])->name('orders.decide');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::get('/reports', ReportController::class)->name('reports.index');
    });
