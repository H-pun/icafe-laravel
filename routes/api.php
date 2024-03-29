<?php

use App\Http\Controllers\BillingController;
use App\Http\Controllers\RedeemController;
use App\Http\Controllers\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('webhook', [WebhookController::class, 'webhook']);
Route::post('redeem/generate', [RedeemController::class, 'generate']);
Route::post('redeem/verify', [RedeemController::class, 'verify']);
Route::post('billing/start', [BillingController::class, 'start']);
Route::get('billing/verify', [BillingController::class, 'verify']);
