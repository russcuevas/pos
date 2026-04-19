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


// ADMIN DASHBOARD
Route::get('/admin/dashboard', [AdminDashboardController::class, 'AdminDashboardPage'])->name('admin.dashboard.page');
Route::get('/admin/cashiers', [AdminCashiersController::class, 'AdminCashiersPage'])->name('admin.cashiers.page');
Route::get('/admin/pos', [AdminPOSController::class, 'AdminPOSPage'])->name('admin.pos.page');
Route::get('/admin/products', [AdminProductsController::class, 'AdminProductsPage'])->name('admin.products.page');
Route::get('/admin/inventory', [AdminInventoryController::class, 'AdminInventoryPage'])->name('admin.inventory.page');
