<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockManagementController;
use App\Http\Controllers\SupplierController;

Route::get('/', function () {
    return redirect('/admin/dashboard');
});

Route::get('/dashboard', function () {
    return redirect('/admin/dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth::routes();

Route::prefix('admin')->middleware('auth')->group(function () {

    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);


//     Route::get('/', [HomeController::class, 'index'])->name('home');
    // Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    // Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
    Route::resource('products', ProductController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('categories', CategoryController::class);

    // ORDERS

    Route::resource('orders', OrderController::class);

    // STOCK MANAGEMENT
    Route::get('/stock-management', [StockManagementController::class, 'index'])->name('stocks.index');
    Route::post('/stock-management/add/{productId}', [StockManagementController::class, 'add'])->name('stocks.add');
    Route::post('/stock-management/adject/{productId}', [StockManagementController::class, 'adject'])->name('stocks.adject');


    // POS
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/pay', [PosController::class, 'pay'])->name('pos.pay');

    // POS CART
    Route::get('/cart/add-to-cart/{productId}', [CartController::class, 'addToCart'])->name('cart.addToCart');
    Route::get('/cart/remove-from-cart/{productId}', [CartController::class, 'removeFromCart'])->name('cart.removeFromCart');
    Route::delete('/cart/empty', [CartController::class, 'empty'])->name('cart.empty');



    // Route::resource('orders', OrderController::class);

    // Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    // Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    // Route::post('/cart/change-qty', [CartController::class, 'changeQty']);
    // Route::delete('/cart/delete', [CartController::class, 'delete']);
    // Route::delete('/cart/empty', [CartController::class, 'empty']);

    // Transaltions route for React component
    // Route::get('/locale/{type}', function ($type) {
    //     $translations = trans($type);
    //     return response()->json($translations);
    // });
});

require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
