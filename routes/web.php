<?php

use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\DailyReportVerifyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderApprovedRefundsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderInitiatedPaymentsController;
use App\Http\Controllers\OrderInitiatedRefundsController;
use App\Http\Controllers\OrderInvoicesController;
use App\Http\Controllers\OrderPaymentsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PointOfSaleController;
use App\Http\Controllers\RemittanceController;
use App\Http\Controllers\StockProductBatchController;
use App\Http\Controllers\StockProductController;
use App\Http\Controllers\StockProductPendingController;
use App\Http\Controllers\WithdrawalRequestApprovedController;
use App\Http\Controllers\WithdrawalRequestController;
use App\Http\Controllers\WithdrawalRequestPendingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->to('/dashboard');
});

Route::middleware([
    'auth',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::group(['prefix' => 'pos', 'middleware' => 'auth'], function () {
    Route::get('/', [PointOfSaleController::class, 'index'])->name('pos');
    Route::get('/payments', [PaymentController::class, 'index'])->name('payment.index');
});

// Route::group(['prefix' => 'pos', 'middleware' => 'auth'], function () {
//     Route::get('/', [PaymentController::class, 'create'])->name('payment.create');
//     Route::get('/payments', [PaymentController::class, 'index'])->name('payment.index');
// });

Route::group(['prefix' => 'inventory', 'middleware' => 'auth'], function () {
    Route::get('/', [StockProductController::class, 'index'])->name('inventory.index');
    Route::get('/pending', StockProductPendingController::class)->name('inventory.pending');
    Route::get('/new', [StockProductController::class, 'create'])->name('inventory.create');
    Route::get('/edit/{stockProduct}', [StockProductController::class, 'edit'])->name('inventory.edit');
    Route::get('/batches/upload', [StockProductBatchController::class, 'upload'])->name('inventory.batch-upload');
});

Route::group(['prefix' => 'orders', 'middleware' => 'auth'], function () {
    Route::get('', [OrderController::class, 'index'])->name('order.index');
    Route::get('/initiated-payments', OrderInitiatedPaymentsController::class)->name('order.initiated-payments');
    Route::get('/{order}/invoices', OrderInvoicesController::class)->name('order.invoices');
    Route::get('/{order}/payments', OrderPaymentsController::class)->name('order.payments');
    Route::get('/initiated-refunds', OrderInitiatedRefundsController::class)->name('order.initiated-refunds');
    Route::get('/approved-refunds', OrderApprovedRefundsController::class)->name('order.approved-refunds');
    Route::get('{order}', [OrderController::class, 'show'])->name('order.show');
});

Route::group(['prefix' => 'withdrawal-requests', 'middleware' => 'auth'], function () {
    Route::get('/', [WithdrawalRequestController::class, 'index'])->name('withdrawal-request.index');
    Route::get('/approved', WithdrawalRequestApprovedController::class)->name('withdrawal-request.approved');
    Route::get('/pending', WithdrawalRequestPendingController::class)->name('withdrawal-request.pending');
    Route::get('/new', [WithdrawalRequestController::class, 'create'])->name('withdrawal-request.create');
});

Route::group(['prefix' => 'remittances', 'middleware' => 'auth'], function () {
    Route::get('/', [RemittanceController::class, 'index'])->name('remittance.index');
    Route::get('/accepted', [RemittanceController::class, 'accepted'])->name('remittance.accepted');
    Route::get('/due', [RemittanceController::class, 'due'])->name('remittance.due');
});

Route::group(['prefix' => 'daily-reports', 'middleware' => 'auth'], function () {
    Route::get('/', DailyReportController::class)->name('daily-report');
    Route::get('/{dailyReport}', DailyReportVerifyController::class )->name('daily-report.view');
});
