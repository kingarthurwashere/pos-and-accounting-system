<?php

namespace App\Livewire;

use App\Models\Payment;
use App\Models\Refund;
use App\Models\Remittance;
use App\Models\WithdrawalRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class DailyReportVerify extends Component
{
    public $report;

    public function render()
    {
        return view('livewire.daily-report.verify', [
            'report' => $this->report,
        ]);
    }

    public function verifyDailyReport()
    {
        Gate::authorize('verify', $this->report);

        $this->report->verifier = auth()->user()->id;
        $this->report->verified_at = Carbon::now();
        $this->report->status = 'VERIFIED';
        $this->report->save();
    }
}
