<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderTrackingController;

Route::get('/', [LandingController::class, 'index'])->name('landing.index');
Route::get('/catalog', [LandingController::class, 'catalog'])->name('landing.catalog');

Route::get('/order/tracking', [OrderTrackingController::class, 'index'])->name('order.tracking');
Route::get('/order/{service}', [OrderController::class, 'show'])->name('order.show');
Route::get('/order/{orderNumber}/success', [OrderController::class, 'success'])->name('order.success');
Route::get('/order/{orderNumber}/invoice', [OrderController::class, 'invoice'])->name('order.invoice');

// Admin SPA Route
Route::get('/admin/{any?}', function () {
    return view('admin');
})->where('any', '.*')->name('admin.spa');
