<?php

use App\Http\Controllers\admin\AdminCashiersController;
use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\AdminInventoryController;
use App\Http\Controllers\admin\AdminPOSController;
use App\Http\Controllers\admin\AdminProductsController;
use App\Http\Controllers\auth\AuthController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

// AUTH

Route::get('/admin/login', [AuthController::class, 'AdminLoginPage'])->name('admin.login.page');
Route::post('/admin/login/request', [AuthController::class, 'AdminLoginRequest'])->name('admin.login.request');
Route::post('/admin/logout', [AuthController::class, 'AdminLogout'])->name('admin.logout');

// ADMIN ROUTES
Route::middleware(['admin'])->group(function () {
    // ADMIN DASHBOARD
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'AdminDashboardPage'])->name('admin.dashboard.page');

    // ADMIN CASHIER CRUD
    Route::get('/admin/cashiers', [AdminCashiersController::class, 'AdminCashiersPage'])->name('admin.cashiers.page');
    Route::post('/admin/cashiers/create', [AdminCashiersController::class, 'AdminCashiersCreate'])->name('admin.cashiers.create');
    Route::put('/admin/cashiers/{id}/update', [AdminCashiersController::class, 'AdminCashiersUpdate'])->name('admin.cashiers.update');
    Route::delete('/admin/cashiers/{id}/delete', [AdminCashiersController::class, 'AdminCashiersDelete'])->name('admin.cashiers.delete');

    // ADMIN POS
    Route::get('/admin/pos', [AdminPOSController::class, 'AdminPOSPage'])->name('admin.pos.page');
    Route::post('/admin/pos/cart/add', [AdminPOSController::class, 'AdminAddToCart'])->name('admin.pos.cart.add');
    Route::post('/admin/pos/cart/custom', [AdminPOSController::class, 'AdminAddCustomCart'])->name('admin.pos.cart.custom');
    Route::post('/admin/pos/cart/save', [AdminPOSController::class, 'AdminSaveOrder'])->name('admin.pos.cart.save');
    Route::post('/admin/pos/saved-order/{reference}/load', [AdminPOSController::class, 'AdminLoadSavedOrder'])->name('admin.pos.saved.load');
    Route::delete('/admin/pos/saved-order/{reference}/delete', [AdminPOSController::class, 'AdminDeleteSavedOrder'])->name('admin.pos.saved.delete');
    Route::post('/admin/pos/cart/{id}/update', [AdminPOSController::class, 'AdminUpdateCart'])->name('admin.pos.cart.update');
    Route::delete('/admin/pos/cart/{id}/delete', [AdminPOSController::class, 'AdminDeleteCart'])->name('admin.pos.cart.delete');
    Route::post('/admin/pos/cart/checkout', [AdminPOSController::class, 'AdminCheckout'])->name('admin.pos.cart.checkout');

    Route::get('/admin/products', [AdminProductsController::class, 'AdminProductsPage'])->name('admin.products.page');
    Route::post('/admin/products/create', [AdminProductsController::class, 'AdminProductsCreate'])->name('admin.products.create');
    Route::put('/admin/products/{id}/update', [AdminProductsController::class, 'AdminProductsUpdate'])->name('admin.products.update');
    Route::delete('/admin/products/{id}/delete', [AdminProductsController::class, 'AdminProductsDelete'])->name('admin.products.delete');
    Route::get('/admin/inventory', [AdminInventoryController::class, 'AdminInventoryPage'])->name('admin.inventory.page');
});
