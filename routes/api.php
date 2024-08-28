<?php

use App\Http\Controllers\Api\OnlineOrderController;
use App\Http\Controllers\Api\PayableReceiveRemittanceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/online-orders/sync', [OnlineOrderController::class, 'sync']);
Route::post('/payables/receive-remittance', PayableReceiveRemittanceController::class);
