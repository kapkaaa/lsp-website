<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\OperationalHourController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\ProductPhotoController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public home route
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Admin routes group
Route::prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master data routes
    Route::resource('brands', BrandController::class);
    Route::resource('products', ProductController::class);
    Route::resource('product-details', ProductDetailController::class);
    Route::resource('product-photos', ProductPhotoController::class);
    Route::resource('types', TypeController::class);

    // Settings routes
    Route::resource('operational-hours', OperationalHourController::class);
    Route::resource('users', UserController::class);
});
