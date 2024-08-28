<?php

namespace App\Livewire\Forms;

use App\Models\DailyReport;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\WithdrawalRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Rule;
use Livewire\Form;

class DailyReportConfirmRequestForm extends Form
{

    #[Rule('required')]
    public $assignedVerifier;

    public function confirmDailyReport()
    {
        $user = auth()->user();
        $currentDate = Carbon::now()->toDateString();
        $report = DailyReport::where('user_id', $user->id)
            ->where('current_date', $currentDate)
            ->first();

        //Gate::authorize('confirm', $report);
        $report->status = 'CONFIRMED';
        $report->cash_in_hand = $report->closing_balance - $report->opening_balance;
        $report->assigned_verifier = $this->assignedVerifier;
        $report->save();
    }

}
