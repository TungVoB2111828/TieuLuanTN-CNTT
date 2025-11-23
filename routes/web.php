<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashBoardController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StaffRegisterController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\Auth\StaffLoginController;
use App\Http\Controllers\Admin\ProductCrudController;
use App\Http\Controllers\Admin\StaffCrudController;
use App\Http\Controllers\Admin\CategoryCrudController;
use App\Http\Controllers\Admin\InvoiceCrudController;
use App\Http\Controllers\Admin\UsersController;


Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/orders', [OrderController::class, 'index'])->name('orders');
//-----------------------------------------------------------------------------------------------------
// Đăng nhập User
Route::get('/login/user', [LoginController::class, 'showUserLoginForm'])->name('login.user');
Route::post('/login/user', [LoginController::class, 'loginUser']);

// Đăng nhập Staff
Route::get('/login/staff', [LoginController::class, 'showStaffLoginForm'])->name('login.staff');
Route::post('/login/staff', [LoginController::class, 'loginStaff']);
//-----------------------------------------------------------------------------------------------------

Route::middleware('auth')->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
});

// Trang chính
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/edit/info', [ProfileController::class, 'editInfo'])->name('profile.edit.info');
    Route::get('/profile/edit/password', [ProfileController::class, 'editPassword'])->name('profile.edit.password');
    Route::get('/profile/edit/delete', [ProfileController::class, 'editDelete'])->name('profile.edit.delete');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::post('/orders/cancel/{invoice}', [OrderController::class, 'cancel'])->name('orders.cancel');
});

// Giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Toggle yêu thích
Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

// Sản phẩm
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/category/{id}', [ProductController::class, 'category'])->name('products.category');

// Admin dashboard

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('index', [DashBoardController::class, 'index'])->name('home.index');
    Route::get('/data/time', [DashboardController::class, 'getTimeData']);
    Route::get('/data/customer', [DashboardController::class, 'getCustomerData']);
    Route::get('/data/product', [DashboardController::class, 'getProductData']);

    Route::get('/products', [ProductCrudController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductCrudController::class, 'create'])->name('products.create'); // Route mới được thêm
    Route::post('/products', [ProductCrudController::class, 'store'])->name('products.store');
    Route::get('/products/{id}', [ProductCrudController::class, 'show'])->name('products.show');
    Route::get('/products/{id}/edit', [ProductCrudController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductCrudController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}', [ProductCrudController::class, 'destroy'])->name('products.destroy');

    Route::get('/staff', [StaffCrudController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffCrudController::class, 'create'])->name('staff.create');// Route mới được thêm
    Route::post('/staff', [StaffCrudController::class, 'store'])->name('staff.store');
    Route::get('/staff/{id}', [StaffCrudController::class, 'show'])->name('staff.show');
    Route::get('/staff/{id}/edit', [StaffCrudController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{id}', [StaffCrudController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{id}', [StaffCrudController::class, 'destroy'])->name('staff.destroy');

    Route::get('/category', [CategoryCrudController::class, 'index'])->name('category.index');
    Route::get('/category/create', [CategoryCrudController::class, 'create'])->name('category.create');// Route mới được thêm
    Route::post('/category', [CategoryCrudController::class, 'store'])->name('category.store');
    Route::get('/category/{id}', [CategoryCrudController::class, 'show'])->name('category.show');
    Route::get('/category/{id}/edit', [CategoryCrudController::class, 'edit'])->name('category.edit');
    Route::put('/category/{id}', [CategoryCrudController::class, 'update'])->name('category.update');
    Route::delete('/category/{id}', [CategoryCrudController::class, 'destroy'])->name('category.destroy');

    Route::get('/invoice', [InvoiceCrudController::class, 'index'])->name('invoice.index');
    Route::get('/invoice/create', [InvoiceCrudController::class, 'create'])->name('invoice.create');// Route mới được thêm
    Route::post('/invoice', [InvoiceCrudController::class, 'store'])->name('invoice.store');
    Route::get('/invoice/{id}', [InvoiceCrudController::class, 'show'])->name('invoice.show');
    Route::get('/invoice/{id}/edit', [InvoiceCrudController::class, 'edit'])->name('invoice.edit');
    Route::put('/invoice/{id}', [InvoiceCrudController::class, 'update'])->name('invoice.update');
    Route::delete('/invoice/{id}', [InvoiceCrudController::class, 'destroy'])->name('invoice.destroy');
    Route::get('/admin/invoice/{id}', [InvoiceCrudController::class, 'show'])->name('admin.invoice.show');

    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UsersController::class, 'destroy'])->name('users.destroy');

});

// Auth routes
require __DIR__.'/auth.php';

// Staff register
Route::prefix('staff')->name('staff.')->group(function () {
    Route::get('register', [StaffRegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [StaffRegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');