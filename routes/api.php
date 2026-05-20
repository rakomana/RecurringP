<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentAttemptController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UsageRecordController;
use App\Http\Controllers\WebhookEventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::apiResource('users', UserController::class);
Route::apiResource('plans', PlanController::class);
Route::apiResource('subscriptions', SubscriptionController::class);
Route::apiResource('invoices', InvoiceController::class);
Route::apiResource('payments', PaymentController::class);
Route::apiResource('payment-attempts', PaymentAttemptController::class);
Route::apiResource('webhook-events', WebhookEventController::class);
Route::apiResource('usage-records', UsageRecordController::class);
