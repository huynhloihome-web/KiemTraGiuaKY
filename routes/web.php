<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductManageController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/quan-ly-san-pham', [ProductManageController::class, 'index'])->name('productlist');
    Route::get('/quan-ly-san-pham/xem/{id}', [ProductManageController::class, 'detail'])->name('productdetail');
    Route::post('/quan-ly-san-pham/xoa', [ProductManageController::class, 'delete'])->name('productdelete');
});

require __DIR__.'/auth.php';
