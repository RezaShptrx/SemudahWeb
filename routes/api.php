<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentWebhookController;

Route::post('/midtrans/webhook', [PaymentWebhookController::class, 'handle'])->name('api.midtrans.webhook');

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
    Route::post('logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::post('refresh', [\App\Http\Controllers\Api\AuthController::class, 'refresh']);
    Route::post('me', [\App\Http\Controllers\Api\AuthController::class, 'me']);
});

Route::group([
    'middleware' => 'auth:api',
], function () {
    Route::apiResource('categories', \App\Http\Controllers\Api\CategoryController::class);
    Route::apiResource('services', \App\Http\Controllers\Api\ServiceController::class);
    Route::apiResource('users', \App\Http\Controllers\Api\UserController::class);
    Route::apiResource('promo-codes', \App\Http\Controllers\Api\PromoCodeController::class);
    Route::apiResource('products', \App\Http\Controllers\Api\ProductController::class);
    Route::apiResource('orders', \App\Http\Controllers\Api\OrderController::class);
    Route::apiResource('attendances', \App\Http\Controllers\Api\AttendanceController::class);

    Route::get('settings', [\App\Http\Controllers\Api\SettingController::class, 'index']);
    Route::post('settings', [\App\Http\Controllers\Api\SettingController::class, 'update']);

    Route::get('reports/summary', [\App\Http\Controllers\Api\ReportController::class, 'summary']);
    Route::get('reports/archives', [\App\Http\Controllers\Api\ReportController::class, 'archives']);
});
