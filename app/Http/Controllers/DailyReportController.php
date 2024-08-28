<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\LocationBalance;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\Remittance;
use App\Models\WithdrawalRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DailyReportController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $currentDate = Carbon::now()->toDateString();

        $report = DailyReport::where('user_id', $user->id)
                             ->whereDate('current_date', $currentDate)
                             ->where('location_id', $user->login_location_id)
                             ->first();

        if ($report && $report->status !== 'NOT_CONFIRMED') {
            return $this->handleReportUnavailable();
        }

        return $this->handleReportCreationOrUpdate($report, $user, $currentDate);
    }

    private function handleReportCreationOrUpdate($report, $user, $currentDate)
    {
        $disbursed = $this->tallyDisbursed();
        $received = $this->tallyReceived();
        
        $openingBalance = LocationBalance::where('location_id', auth()->user()->login_location_id)->first()->opening_balance;
        $closingBalance = $openingBalance + $received - $disbursed;

        if (!$report) {
            $report = $this->createNewReport($user, $currentDate, $openingBalance, $closingBalance, $disbursed, $received);
        } else {
            $this->updateReport($report, $openingBalance, $closingBalance, $disbursed, $received);
        }

        $received = $this->paymentsReceivedList();
        $disbursed = $this->tallyDisbursedList();
        $cash_in_hand = number_format(($closingBalance - $openingBalance) / 100, 2);

        return view('daily-report.index', compact('report', 'received', 'disbursed', 'cash_in_hand'));
    }

    private function createNewReport($user, $currentDate, $openingBalance, $closingBalance, $disbursed, $received)
    {
        try {
            return DailyReport::create([
                'current_date' => $currentDate,
                'location_id' => $user->login_location_id,
                'user_id' => $user->id,
                'opening_balance' => $openingBalance,
                'closing_balance' => $closingBalance,
                'total_disbursed' => $disbursed,
                'total_received' => $received,
                'status' => 'NOT_CONFIRMED' // Assuming default status
            ]);
        } catch (\Throwable $th) {
            Log::error('Failed to create DailyReport: ' . $th->getMessage());
            abort(500, 'Unable to create report');
        }
    }

    private function updateReport($report, $openingBalance, $closingBalance, $disbursed, $received)
    {
        $report->update([
            'opening_balance' => $openingBalance,
            'closing_balance' => $closingBalance,
            'total_disbursed' => $disbursed,
            'total_received' => $received
        ]);
    }

    private function handleReportUnavailable()
    {
        abort(403, 'Daily report is confirmed and cannot be updated.');
    }

    public function show(DailyReport $dailyReport)
    {

        $report = $dailyReport;

        return view('daily-report.show', compact('report'));
    }

    private function withdrawalRequestsDisbursed($userId = null)
    {
        $totalAmount = 0;
        $today = Carbon::today();

        $records = WithdrawalRequest::whereDate('disburse_datetime', $today)
            ->where('disbursed_by', $userId ?? auth()->user()->id)
            ->get();

        $totalAmount += $records->sum('amount');

        return $totalAmount;
    }

    private function withdrawalRequestsDisbursedList($userId = null)
    {
        $today = Carbon::today();
        $list = [];

        $records = WithdrawalRequest::whereDate('disburse_datetime', $today)
            ->where('disbursed_by', $userId ?? auth()->user()->id)
            ->get();

        foreach ($records as $r) {
            $list[] = [
                'amount' => $r->formattedAmount(),
                'source' => 'Withdral Requests - ' . $r->type,
                'created_at' => $r->disburse_datetime->diffForHumans(),
            ];
        }

        return $list;
    }

    private function refundsDisbursed($userId = null)
    {
        $totalAmount = 0;
        $today = Carbon::today();

        $records = Refund::whereDate('disbursement_datetime', $today)
            ->where('disbursed_by', $userId ?? auth()->user()->id)
            ->get();

        $totalAmount += $records->sum('amount');

        return $totalAmount;
    }

    private function refundsDisbursedList($userId = null)
    {
        $list = [];
        $today = Carbon::today();

        $records = Refund::whereDate('disbursement_datetime', $today)
            ->where('disbursed_by', $userId ?? auth()->user()->id)
            ->get();

        foreach ($records as $r) {
            $list[] = [
                'amount' => $r->formattedAmount(),
                'source' => 'Refund',
                'created_at' => $r->disbursement_datetime->diffForHumans(),
            ];
        }

        return $list;
    }

    private function remittancesDisbursed($userId = null)
    {
        $totalAmount = 0;
        $today = Carbon::today();

        $records = Remittance::whereDate('disbursed_at', $today)
            ->where('disbursed_by', $userId ?? auth()->user()->id)
            ->get();

        $totalAmount += $records->sum('receivable');

        return $totalAmount;
    }

    private function remittancesDisbursedList($userId = null)
    {
        $list = [];
        $today = Carbon::today();

        $records = Remittance::whereDate('disbursed_at', $today)
            ->where('disbursed_by', $userId ?? auth()->user()->id)
            ->get();

        foreach ($records as $r) {
            $list[] = [
                'amount' => $r->formattedReceivable(),
                'source' => 'Remittance',
                'created_at' => $r->disbursed_at->diffForHumans(),
            ];
        }

        return $list;
    }

    private function paymentsReceived($userId = null)
    {
        $totalAmount = 0;
        $today = Carbon::today();

        $records = Payment::whereDate('created_at', $today)
            ->where('receiving_cashier', $userId ?? auth()->user()->id)
            ->get();

        $totalAmount += $records->sum('received_amount');

        return $totalAmount;
    }

    private function paymentsReceivedList($userId = null)
    {
        $today = Carbon::today();
        $list = [];

        $records = Payment::whereDate('created_at', $today)
            ->where('receiving_cashier', $userId ?? auth()->user()->id)
            ->get();

        foreach ($records as $r) {
            $list[] = [
                'amount' => $r->formattedReceivedAmount(),
                'source' => $r->payable->name,
                'created_at' => $r->created_at->diffForHumans(),
            ];
        }

        return $list;
    }

    private function tallyDisbursed()
    {
        return $this->withdrawalRequestsDisbursed() + $this->refundsDisbursed() + $this->remittancesDisbursed();
    }

    private function tallyDisbursedList()
    {
        return array_merge($this->withdrawalRequestsDisbursedList(), $this->refundsDisbursedList(), $this->remittancesDisbursedList());
    }

    private function tallyReceived()
    {
        return $this->paymentsReceived();
    }
}
