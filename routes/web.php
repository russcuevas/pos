<?php

use App\Http\Controllers\admin\AdminCashiersController;
use App\Http\Controllers\admin\AdminOrdersController;
use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\AdminInventoryController;
use App\Http\Controllers\admin\AdminPendingOrdersController;
use App\Http\Controllers\admin\AdminPOSController;
use App\Http\Controllers\admin\AdminProductsController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\customers\CustomersHomeController;
use App\Http\Controllers\customers\CustomersCartController;
use App\Http\Controllers\customers\CustomersOrderController;
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

// CUSTOMER AUTH
Route::get('/customers/login', [AuthController::class, 'CustomerLoginPage'])->name('customers.login.page');
Route::post('/customers/login/request', [AuthController::class, 'CustomerLoginRequest'])->name('customers.login.request');
Route::get('/customers/register', [AuthController::class, 'CustomerRegisterPage'])->name('customers.register.page');
Route::post('/customers/register/request', [AuthController::class, 'CustomerRegisterRequest'])->name('customers.register.request');
Route::get('/customers/verify/{id}', [AuthController::class, 'VerifyCustomer'])->name('customers.verify');
Route::post('/customers/logout', [AuthController::class, 'CustomerLogout'])->name('customers.logout');

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

    // ADMIN PRODUCTS
    Route::get('/admin/products', [AdminProductsController::class, 'AdminProductsPage'])->name('admin.products.page');
    Route::post('/admin/products/create', [AdminProductsController::class, 'AdminProductsCreate'])->name('admin.products.create');
    Route::put('/admin/products/{id}/update', [AdminProductsController::class, 'AdminProductsUpdate'])->name('admin.products.update');
    Route::delete('/admin/products/{id}/delete', [AdminProductsController::class, 'AdminProductsDelete'])->name('admin.products.delete');

    // ADMIN INVENTORY
    Route::get('/admin/inventory', [AdminInventoryController::class, 'AdminInventoryPage'])->name('admin.inventory.page');
    Route::post('/admin/inventory/stock/{productId}/add', [AdminInventoryController::class, 'AdminAddStock'])->name('admin.inventory.addStock');
    Route::put('/admin/inventory/stock/{historyId}/update', [AdminInventoryController::class, 'AdminUpdateStock'])->name('admin.inventory.updateStock');
    Route::delete('/admin/inventory/stock/{historyId}/delete', [AdminInventoryController::class, 'AdminDeleteStock'])->name('admin.inventory.deleteStock');

    // ADMIN PENDING ORDERS
    Route::get('/admin/pending_orders', [AdminPendingOrdersController::class, 'AdminPendingOrdersPage'])->name('admin.pending_orders.page');
    Route::post('/admin/pending_orders/update-quantity', [AdminPendingOrdersController::class, 'UpdateOrderQuantity'])->name('admin.pending_orders.update_qty');
    Route::post('/admin/pending_orders/add-item', [AdminPendingOrdersController::class, 'AddOrderItem'])->name('admin.pending_orders.add_item');
    Route::post('/admin/pending_orders/send-chat', [AdminPendingOrdersController::class, 'SendChat'])->name('admin.pending_orders.send_chat');
    Route::get('/admin/pending_orders/messages/{order_number}', [AdminPendingOrdersController::class, 'GetMessages'])->name('admin.pending_orders.get_messages');
    Route::post('/admin/pending_orders/cancel', [AdminPendingOrdersController::class, 'CancelOrder'])->name('admin.pending_orders.cancel');
    Route::post('/admin/pending_orders/start-preparing', [AdminPendingOrdersController::class, 'StartPreparing'])->name('admin.pending_orders.start_preparing');
    Route::post('/admin/pending_orders/mark-ready', [AdminPendingOrdersController::class, 'MarkAsReady'])->name('admin.pending_orders.mark_ready');
    Route::get('/admin/pending_orders/check', [AdminPendingOrdersController::class, 'CheckNewOrders'])->name('admin.pending_orders.check');
    Route::get('/admin/pending_orders/fetch-card/{order_number}', [AdminPendingOrdersController::class, 'FetchOrderCard'])->name('admin.pending_orders.fetch_card');
    Route::post('/admin/pending_orders/checkout', [AdminPendingOrdersController::class, 'CheckoutOrder'])->name('admin.pending_orders.checkout');
    // ADMIN ORDERS
    Route::get('/admin/orders', [AdminOrdersController::class, 'AdminOrdersPage'])->name('admin.orders.page');
});


Route::middleware(['customer'])->group(function () {
    // CUSTOMERS HOME PAGE
    Route::get('customers/home', [CustomersHomeController::class, 'CustomerHomePage'])->name('customers.home.page');

    // CUSTOMERS CART
    Route::post('customers/cart/add', [CustomersCartController::class, 'CustomersAddToCart'])->name('customers.cart.add');
    Route::get('customers/cart/get', [CustomersCartController::class, 'CustomersGetCart'])->name('customers.cart.get');
    Route::post('customers/cart/update', [CustomersCartController::class, 'CustomersUpdateCart'])->name('customers.cart.update');
    Route::post('customers/cart/delete', [CustomersCartController::class, 'CustomersDeleteCart'])->name('customers.cart.delete');
    Route::post('customers/cart/checkout', [CustomersCartController::class, 'CustomersCheckout'])->name('customers.cart.checkout');

    // CUSTOMERS ORDERS
    Route::get('customers/my_orders', [CustomersOrderController::class, 'CustomersOrdersPage'])->name('customers.orders.page');
    Route::post('customers/orders/update-quantity', [CustomersOrderController::class, 'UpdateOrderQuantity'])->name('customers.orders.update_qty');
    Route::post('customers/orders/send-chat', [CustomersOrderController::class, 'SendChat'])->name('customers.orders.send_chat');
    Route::get('customers/orders/messages/{order_number}', [CustomersOrderController::class, 'GetMessages'])->name('customers.orders.get_messages');
    Route::post('customers/orders/add-item', [CustomersOrderController::class, 'AddOrderItem'])->name('customers.orders.add_item');
    Route::get('customers/orders/status', [CustomersOrderController::class, 'GetOrdersStatus'])->name('customers.orders.get_status');
});
