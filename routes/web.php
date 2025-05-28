<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController; // Don't forget to import if used

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

// Welcome Route (Accessible to everyone)
Route::get('/', function () {
    return view('welcome');
});

// Dashboard Route (Requires authentication and email verification)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ---
## Authenticated User Routes

// All routes within this group require the user to be authenticated.
Route::middleware('auth')->group(function () {

    // Profile Management Routes (Accessible to all authenticated users)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ---
    ## Role-Based Access Control

    // Admin Specific Routes
    Route::middleware('role:admin')->group(function () {
        // Full CRUD for Menus
        Route::resource('menus', MenuController::class)->names([ // Explicitly name for clarity
            'index' => 'admin.menus.index',
            'create' => 'admin.menus.create',
            'store' => 'admin.menus.store',
            'show' => 'admin.menus.show',
            'edit' => 'admin.menus.edit',
            'update' => 'admin.menus.update',
            'destroy' => 'admin.menus.destroy',
        ]);

        // Full CRUD for Tables
        Route::resource('tables', TableController::class)->names([ // Explicitly name for clarity
            'index' => 'admin.tables.index',
            'create' => 'admin.tables.create',
            'store' => 'admin.tables.store',
            'show' => 'admin.tables.show',
            'edit' => 'admin.tables.edit',
            'update' => 'admin.tables.update',
            'destroy' => 'admin.tables.destroy',
        ]);

        // Admin's Orders Dashboard (Specific route before resource)
        Route::get('orders/dashboard', [OrderController::class, 'dashboard'])->name('admin.orders.dashboard');

        // Admin can manage all orders (Full CRUD).
        // Place this AFTER any more specific 'orders' routes for admin.
        Route::resource('orders', OrderController::class)->names([
            'index' => 'admin.orders.index',
            'create' => 'admin.orders.create',
            'store' => 'admin.orders.store',
            'show' => 'admin.orders.show',
            'edit' => 'admin.orders.edit',
            'update' => 'admin.orders.update',
            'destroy' => 'admin.orders.destroy',
        ]);

        // Admin can update any order's status
        // Place this AFTER the resource route to avoid conflicts if 'status' could be mistaken for an {order} ID.
        Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

        // Reports for Admin
        Route::get('/reports/generate-sales', [ReportController::class, 'generateSalesReport'])->name('admin.reports.generateSales');
    });

    // Waiter Specific Routes
    Route::middleware('role:waiter')->group(function () {
        // Waiter can view Menus (only index, not CRUD)
        Route::get('menus', [MenuController::class, 'index'])->name('waiter.menus.index');

        // Waiter can view Tables (only index, not CRUD)
        Route::get('tables', [TableController::class, 'index'])->name('waiter.tables.index');

        // Waiter's Orders Management (Can create, view their own, update, but NOT delete)
        // Note: The `show` method was problematic if you wanted `index` but not `show` for waiter.
        // It's generally better to define specific routes or use `only()`/`except()` carefully.
        // For waiter: `index`, `create`, `store`, `edit`, `update`.
        Route::resource('orders', OrderController::class)->only(['index', 'create', 'store', 'edit', 'update'])->names([
            'index' => 'waiter.orders.index',
            'create' => 'waiter.orders.create',
            'store' => 'waiter.orders.store',
            'edit' => 'waiter.orders.edit',
            'update' => 'waiter.orders.update',
        ]);

        // Waiter can update order status
        Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('waiter.orders.updateStatus');
    });
});

// ---
## Authentication Routes

// Include Laravel Breeze authentication routes (login, register, reset password, etc.)
require __DIR__.'/auth.php';