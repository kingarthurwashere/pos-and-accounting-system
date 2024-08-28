<?php

namespace App\Http\Controllers;

use App\Enums\InvoiceStatus;
use App\Enums\RefundStatus;
use App\Enums\RemittanceStatus;
use App\Enums\WithdrawalRequestStatus;
use App\Models\City;
use App\Models\CityBalance;
use App\Models\DailyReport;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\Remittance;
use App\Models\StockProduct;
use App\Models\WithdrawalRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        $balances = CityBalance::get();
        $initiated_payments = Invoice::whereIn('status', [InvoiceStatus::DRAFT, InvoiceStatus::NOTPAID]);
        $initiated_refunds = Refund::where('status', RefundStatus::INITIATED);
        $approved_refunds = Refund::where('status', 'APPROVED');
        $pending_withdrawals = WithdrawalRequest::where('status', WithdrawalRequestStatus::PENDING);
        $approval_withdrawals = WithdrawalRequest::where('status', WithdrawalRequestStatus::APPROVED);
        $stock_items = StockProduct::all();
        $pending_stock_items = StockProduct::where('price_approved', '0');
        $remittance_all = Remittance::query();
        $remittance_awaiting_pickup = Remittance::where('status', RemittanceStatus::AWAITING_PICKUP);
        $remittance_accepted = Remittance::where('status', RemittanceStatus::ACCEPTED);

        $reportForVerification = DailyReport::whereDate('created_at', Carbon::today())
                ->where('status', 'CONFIRMED')
                ->where('location_id', auth()->user()->login_location_id)
                ->where('assigned_verifier', auth()->user()->id)
                ->first();

        return view('dashboard', [
            'location' => auth()->user()->location,
            'initiated_payments' => $initiated_payments->count(),
            'initiated_refunds' => $initiated_refunds->count(),
            'approved_refunds' => $approved_refunds->count(),
            'pending_withdrawals' => $pending_withdrawals->count(),
            'approval_withdrawals' => $approval_withdrawals->count(),
            'pending_stock_items' => $pending_stock_items->count(),
            'stock_items' => $stock_items->count(),
            'remittances' => $remittance_all->count(),
            'reportForVerification' => $reportForVerification,
            'remittance_awaiting_pickup' => $remittance_awaiting_pickup->count(),
            'remittance_accepted' => $remittance_accepted->count(),
            'overall_balance' => number_format($balances->map(function ($city) {
                return $city->usd_balance / 100;
            })->sum(), 2),
        ]);
    }
}
