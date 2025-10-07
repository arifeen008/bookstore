<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin\BookController as AdminBookController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// หน้าแรก
Route::get('/', [BookController::class, 'index'])->name('home');

// -------------------- AUTH --------------------
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// -------------------- BOOKS --------------------
Route::get('/books', [BookController::class, 'allBooks'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

Route::middleware('auth')->group(function () {
// -------------------- CART --------------------
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{book}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{book}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{book}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
// -------------------- CHECKOUT / ORDERS --------------------
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/checkout', [OrderController::class, 'placeOrder'])->name('orders.place');
    // ใช้คูปอง
    Route::post('/coupon/apply', [CouponController::class, 'apply'])->name('coupon.apply');
});

Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    // หน้าโปรไฟล์ และเมนูผู้ใช้ทั่วไป
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [UserController::class, 'update'])->name('profile.update');

    Route::get('/orders', [UserController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [UserController::class, 'orderDetails'])->name('orders.show');
    Route::get('/coupons', [UserController::class, 'coupons'])->name('coupons');
    Route::get('/points', [UserController::class, 'points'])->name('points');

    // จัดการที่อยู่
    Route::get('/address', [AddressController::class, 'index'])->name('address.index');
    Route::post('/address', [AddressController::class, 'store'])->name('address.store');
    Route::put('/address/update/{address}', [AddressController::class, 'update'])->name('address.update');
    Route::delete('/address/{address}', [AddressController::class, 'destroy'])->name('address.delete');
    Route::put('/address/{address}/default', [AddressController::class, 'setDefault'])->name('address.default');

});

// -------------------- ADMIN --------------------
Route::middleware(['auth', 'is_admin'])->prefix('admin')->as('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Books
    Route::resource('books', AdminBookController::class);

    // Orders
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    // Bulk add books
    Route::get('books/bulk', [AdminBookController::class, 'bulk'])->name('books.bulk');
    Route::post('books/bulk', [AdminBookController::class, 'bulkStore'])->name('books.bulk.store');
});
