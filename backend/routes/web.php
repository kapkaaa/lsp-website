<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ShippingRateController;
use App\Http\Controllers\Admin\OperationalHourController;
use App\Http\Controllers\Admin\CustomerServiceController as AdminCSController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Cashier\POSController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\ChatController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home redirect
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isKasir()) {
            return redirect()->route('admin.dashboard'); // Kasir juga ke admin
        }
    }
    return redirect()->route('login');
});

// ==================== Authentication Routes ====================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==================== Customer Routes (Public & Protected) ====================
Route::prefix('customer')->name('customer.')->group(function () {
    
    // Public routes
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/products', [CustomerProductController::class, 'index'])->name('products.index');
    Route::get('/products/{id}', [CustomerProductController::class, 'show'])->name('products.show');
    Route::get('/products/variant/details', [CustomerProductController::class, 'getVariantDetails'])->name('products.variant.details');
    
    // Protected customer routes
    Route::middleware(['auth', 'role:Customer'])->group(function () {
        
        // Cart
        Route::prefix('cart')->name('cart.')->group(function () {
            Route::get('/', [CartController::class, 'index'])->name('index');
            Route::post('/add', [CartController::class, 'add'])->name('add');
            Route::put('/{productDetailId}', [CartController::class, 'update'])->name('update');
            Route::delete('/{productDetailId}', [CartController::class, 'remove'])->name('remove');
            Route::delete('/', [CartController::class, 'clear'])->name('clear');
            Route::get('/count', [CartController::class, 'count'])->name('count');
        });
        
        // Checkout
        Route::prefix('checkout')->name('checkout.')->middleware('operational.hours')->group(function () {
            Route::get('/', [CheckoutController::class, 'index'])->name('index');
            Route::post('/calculate-shipping', [CheckoutController::class, 'calculateShipping'])->name('calculate-shipping');
        });
        
        // Orders
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [CustomerOrderController::class, 'index'])->name('index');
            Route::post('/process', [CustomerOrderController::class, 'process'])->name('process')->middleware('operational.hours');
            Route::get('/{id}', [CustomerOrderController::class, 'show'])->name('show');
            Route::post('/{id}/upload-payment', [CustomerOrderController::class, 'uploadPayment'])->name('upload-payment');
            Route::post('/{id}/cancel', [CustomerOrderController::class, 'cancel'])->name('cancel');
        });
        
        // Chat / Customer Service
        Route::prefix('chat')->name('chat.')->middleware('operational.hours')->group(function () {
            Route::get('/', [ChatController::class, 'index'])->name('index');
            Route::post('/send', [ChatController::class, 'send'])->name('send');
            Route::get('/messages', [ChatController::class, 'getMessages'])->name('messages');
        });
    });
});

// ==================== Admin Routes ====================
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Master Data
    Route::resource('brands', BrandController::class);
    Route::resource('types', TypeController::class);
    Route::resource('colors', ColorController::class);
    Route::resource('sizes', SizeController::class);
    
    // Products
    Route::resource('products', AdminProductController::class);
    Route::get('/products/search/barcode', [AdminProductController::class, 'searchByBarcode'])->name('products.search.barcode');
    Route::get('/products/live-search', [AdminProductController::class, 'liveSearch'])
    ->name('products.live-search');
    
    // Users
    Route::resource('users', UserController::class);
    
    // Shipping Rates
    Route::resource('shipping-rates', ShippingRateController::class);
    
    // Operational Hours
    Route::prefix('operational-hours')->name('operational-hours.')->group(function () {
        Route::get('/', [OperationalHourController::class, 'index'])->name('index');
        Route::get(
            'operational-hours/filter',
            [OperationalHourController::class, 'filter']
        )->name('operational-hours.filter');
        Route::get('/{operationalHour}/edit', [OperationalHourController::class, 'edit'])->name('edit');
        Route::put('/{operationalHour}', [OperationalHourController::class, 'update'])->name('update');
        Route::post('/bulk-update', [OperationalHourController::class, 'bulkUpdate'])->name('bulk-update');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
        Route::get('/profit', [ReportController::class, 'profit'])->name('profit');
        Route::get('/export-pdf', [ReportController::class, 'exportPDF'])->name('export-pdf');
        Route::get('/export-excel', [ReportController::class, 'exportExcel'])->name('export-excel');
    });
});

// ==================== Admin & Kasir Shared Routes ====================
Route::middleware(['auth', 'role:Admin,Kasir'])->prefix('admin')->name('admin.')->group(function () {
    
    // Orders Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [AdminOrderController::class, 'show'])->name('show');
        Route::post('/{order}/verify-payment', [AdminOrderController::class, 'verifyPayment'])->name('verify-payment');
        Route::post('/{order}/reject-payment', [AdminOrderController::class, 'reject'])->name('reject-payment');
        Route::put('/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('update-status');
    });
    
    // Customer Service
    Route::prefix('customer-service')->name('customer-service.')->group(function () {
        Route::get('/', [AdminCSController::class, 'index'])->name('index');
        Route::post('/send', [AdminCSController::class, 'send'])->name('send');
        Route::get('/messages/{userId}', [AdminCSController::class, 'getMessages'])->name('messages');
        Route::get('/unread-count', [AdminCSController::class, 'getUnreadCount'])->name('unread-count');
    });
});

// ==================== Cashier Routes ====================
Route::middleware(['auth', 'role:Kasir'])->prefix('cashier')->name('cashier.')->group(function () {
    
    // Point of Sale (POS)
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [POSController::class, 'index'])->name('index');
        Route::post('/search', [POSController::class, 'searchProduct'])->name('search');
        Route::post('/process', [POSController::class, 'process'])->name('process');
        Route::get('/print/{id}', [POSController::class, 'printReceipt'])->name('print');
        Route::get('/history', [POSController::class, 'getTransactionHistory'])->name('history');
    });
});