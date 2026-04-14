<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LaptopController;
Route::get('/', [LaptopController::class, 'index'])->name('laptop.home');

use App\Http\Controllers\Controller5;
use App\Http\Controllers\ProductManageController;


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/laptop/theloai/{id}', [LaptopController::class, 'index']);

Route::middleware(['auth'])->group(function () {
    Route::get('/quan-ly-san-pham', [ProductManageController::class, 'index'])->name('productlist');
    Route::get('/quan-ly-san-pham/xem/{id}', [ProductManageController::class, 'detail'])->name('productdetail');
    Route::post('/quan-ly-san-pham/xoa', [ProductManageController::class, 'delete'])->name('productdelete');
});
Route::get('/timkiem', [Controller5::class, 'timkiem'])->name('laptop.timkiem');

use App\Http\Controllers\LaptopDetailController;

// Route chi tiết sản phẩm và giỏ hàng
Route::get('/laptop/{id}', [LaptopDetailController::class, 'show'])->name('laptop.detail');
Route::post('/cart/add', [LaptopDetailController::class, 'addToCart'])->name('cart.add');
Route::get('/gio-hang', [LaptopDetailController::class, 'viewCart'])->name('cart.view');
Route::post('/cart/remove', [LaptopDetailController::class, 'removeCart'])->name('cart.remove');
Route::post('/cart/checkout', [LaptopDetailController::class, 'checkout'])->name('cart.checkout');

require __DIR__.'/auth.php';
