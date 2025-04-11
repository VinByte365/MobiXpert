<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

// User routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/shop', [HomeController::class, 'shop'])->name('shop');
    Route::get('/product/{id}', [HomeController::class, 'productDetail'])->name('product.detail');
    
    // Cart routes
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart');
        Route::post('/add', [CartController::class, 'add'])->name('cart.add');
        Route::post('/update', [CartController::class, 'update'])->name('cart.update');
        Route::get('/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
        Route::get('/clear', [CartController::class, 'clear'])->name('cart.clear');
    });
    
    Route::get('/checkout', function() {
        return view('home.checkout');
    })->name('checkout');
    
    // Profile routes
    Route::match(['get', 'put'], '/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.update-photo');
    
    // Order routes
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::post('/', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/{order_id}', [OrderController::class, 'show'])->name('orders.show');
    });
});

// Admin routes
Route::prefix('admin')->middleware(['auth', 'verified'])->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    
    // Product routes
    Route::prefix('products')->group(function () {
        Route::get('/', [AdminController::class, 'products'])->name('products');
        Route::get('/create', [AdminController::class, 'create'])->name('products.create'); // Add this line
        Route::get('/data', [AdminController::class, 'getProductsData'])->name('products.data');
        Route::post('/', [AdminController::class, 'store'])->name('products.store');
        Route::get('/{product}/edit', [AdminController::class, 'edit'])->name('products.edit');
        Route::put('/{product}', [AdminController::class, 'update'])->name('products.update');
        Route::delete('/{product}', [AdminController::class, 'destroy'])->name('products.destroy');
        Route::post('/import', [ProductController::class, 'import'])->name('products.import');
        Route::get('/template', [ProductController::class, 'template'])->name('products.template');
    });
    
    // Order management routes
    Route::prefix('orders')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders');
        Route::get('/{order_id}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::put('/{order_id}', [\App\Http\Controllers\Admin\OrderController::class, 'update'])->name('orders.update');
    });
    
    // User management routes
    Route::prefix('users')->group(function () {
        Route::get('/', [AdminController::class, 'users'])->name('users');
        Route::get('/data', [AdminController::class, 'getUsersData'])->name('users.data');
        Route::post('/', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::post('/import', [AdminController::class, 'importUsers'])->name('users.import');
        Route::get('/template', [AdminController::class, 'exportUserTemplate'])->name('users.template');
    });
    
    // Reviews routes
    Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews');
    Route::delete('/reviews/{review}', [AdminController::class, 'destroyReview'])->name('reviews.destroy');
    
    // Profile and Settings routes
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/photo', [AdminController::class, 'updateProfilePhoto'])->name('profile.update-photo');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');


