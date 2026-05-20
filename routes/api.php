<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\PaymentAttemptController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\PlanController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\UsageRecordController;
use App\Http\Controllers\Api\WebhookEventController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => [
    'name' => config('app.name'),
    'status' => 'ok',
]);

Route::controller(AuthController::class)->prefix('auth')->name('auth.')->group(function () {
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
});

Route::middleware('auth:api')->group(function () {
    Route::controller(AuthController::class)->prefix('auth')->name('auth.')->group(function () {
        Route::get('/me', 'me')->name('me');
        Route::post('/logout', 'logout')->name('logout');
    });

    Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{user}', 'show')->name('show');
        Route::match(['put', 'patch'], '/{user}', 'update')->name('update');
        Route::delete('/{user}', 'destroy')->name('destroy');
    });

    Route::controller(CompanyController::class)->prefix('companies')->name('companies.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{company}', 'show')->name('show');
        Route::match(['put', 'patch'], '/{company}', 'update')->name('update');
        Route::delete('/{company}', 'destroy')->name('destroy');
    });

    Route::controller(PlanController::class)->prefix('plans')->name('plans.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{plan}', 'show')->name('show');
        Route::match(['put', 'patch'], '/{plan}', 'update')->name('update');
        Route::delete('/{plan}', 'destroy')->name('destroy');
    });

    Route::controller(SubscriptionController::class)->prefix('subscriptions')->name('subscriptions.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{subscription}', 'show')->name('show');
        Route::match(['put', 'patch'], '/{subscription}', 'update')->name('update');
        Route::delete('/{subscription}', 'destroy')->name('destroy');
    });

    Route::controller(InvoiceController::class)->prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{invoice}', 'show')->name('show');
        Route::match(['put', 'patch'], '/{invoice}', 'update')->name('update');
        Route::delete('/{invoice}', 'destroy')->name('destroy');
    });

    Route::controller(PaymentController::class)->prefix('payments')->name('payments.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{payment}', 'show')->name('show');
        Route::match(['put', 'patch'], '/{payment}', 'update')->name('update');
        Route::delete('/{payment}', 'destroy')->name('destroy');
    });

    Route::controller(PaymentAttemptController::class)->prefix('payment-attempts')->name('payment-attempts.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{paymentAttempt}', 'show')->name('show');
        Route::match(['put', 'patch'], '/{paymentAttempt}', 'update')->name('update');
        Route::delete('/{paymentAttempt}', 'destroy')->name('destroy');
    });

    Route::controller(WebhookEventController::class)->prefix('webhook-events')->name('webhook-events.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{webhookEvent}', 'show')->name('show');
        Route::match(['put', 'patch'], '/{webhookEvent}', 'update')->name('update');
        Route::delete('/{webhookEvent}', 'destroy')->name('destroy');
    });

    Route::controller(UsageRecordController::class)->prefix('usage-records')->name('usage-records.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{usageRecord}', 'show')->name('show');
        Route::match(['put', 'patch'], '/{usageRecord}', 'update')->name('update');
        Route::delete('/{usageRecord}', 'destroy')->name('destroy');
    });
});
