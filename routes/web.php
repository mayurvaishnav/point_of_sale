<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ScheduledJobController;
use App\Mail\AutoReOrderProductsMail;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RoleController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerAccountController;
use App\Http\Controllers\LogViewerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockManagementController;
use App\Http\Controllers\SupplierController;
use App\Jobs\AutoOrderEmailJob;
use App\Models\ScheduledJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

Route::get('/test-auto-order-email-job', function () {
    $job = ScheduledJob::first();
    // // dd($job);
    AutoOrderEmailJob::dispatch($job);
    return 'AutoOrderEmailJob dispatched!';
});

Route::get('/', function () {
    return redirect()->route('dashboard.index');
});

Route::get('home', function () {
    return redirect()->route('dashboard.index');
});

// Route::get('/dashboard', function () {
//     return redirect('/admin/dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/admin/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth::routes();

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {

    Route::get('/logs', [LogViewerController::class, 'index'])->name('logs.index');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);

    Route::resource('products', ProductController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('categories', CategoryController::class);

    // ORDERS
    Route::get('/orders/index', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/layaway', [OrderController::class,'layaway'])->name('orders.layaway');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::post('/orders/{order}/update-customer', [OrderController::class, 'updateCustomer'])->name('orders.updateCustomer');
    Route::get('/orders/{order}/download-invoice', [OrderController::class, 'downloadInvoice'])->name('orders.downloadInvoice');
    Route::post('/orders/{order}/email-invoice', [OrderController::class, 'emailInvoice'])->name('orders.emailInvoice');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // PRINT
    Route::get('/print/receipt/{orderId}', [PrintController::class, 'receipt'])->name('print.receipt');

    // CUSTOMER CREDITS
    Route::get('/customer-accounts', [CustomerAccountController::class, 'index'])->name('customer-accounts.index');
    Route::get('/customer-accounts/{customerAccount}', [CustomerAccountController::class, 'details'])->name('customer-accounts.details');
    Route::post('/customer-accounts/payment/{customerAccount}', [CustomerAccountController::class, 'addPayment'])->name('customer-accounts.addPayment');
    Route::post('/customer-accounts/send-account-email/{customerAccount}', [CustomerAccountController::class, 'sendEmail'])->name('customer-accounts.sendEmail');
    Route::delete('/customer-accounts/payment/{customerAccount}/{customerAccountTransaction}', [CustomerAccountController::class, 'deletePayment'])->name('customer-accounts.deletePayment');

    // STOCK MANAGEMENT
    Route::get('/stock-management', [StockManagementController::class, 'index'])->name('stocks.index');
    Route::post('/stock-management/add/{productId}', [StockManagementController::class, 'add'])->name('stocks.add');
    Route::post('/stock-management/adjust/{productId}', [StockManagementController::class, 'adjust'])->name('stocks.adjust');


    // POS
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/process-payment', [PosController::class, 'processPayment'])->name('pos.processPayment');
    Route::post('/pos/save', [PosController::class, 'save'])->name('pos.save');

    // POS CART
    Route::post('/cart/add-to-cart', [CartController::class, 'addToCart'])->name('cart.addToCart');
    Route::post('/cart/remove-from-cart', [CartController::class, 'removeFromCart'])->name('cart.removeFromCart');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/updateCustomer', [CartController::class, 'updateCustomer'])->name('cart.updateCustomer');
    Route::post('/cart/empty', [CartController::class, 'empty'])->name('cart.empty');
    Route::post('/cart/updateOrderNote', [CartController::class, 'updateOrderNote'])->name('cart.updateOrderNote');


    // REPORTS
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/customer', [ReportController::class, 'customer'])->name('reports.customer');

    //JOBS
    Route::resource('jobs', ScheduledJobController::class);
});

require __DIR__.'/auth.php';

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

