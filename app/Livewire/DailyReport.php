<?php

namespace App\Livewire;

use App\Models\Payment;
use App\Models\Refund;
use App\Models\Remittance;
use App\Models\DailyReport as DR;
use App\Models\WithdrawalRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class DailyReport extends Component
{
    public $report;
    public $received;
    public $cashInHand;
    public $disbursed;
    public $selectedTab = 'received-tab';

    #[On('daily-report-confirmed', 'daily-report-verified')] 
    public function ensureHeaderRefresh()
    {
        // ...
    }

    public function render()
    {
        return view('livewire.daily-report.index', [
            'report' => $this->report,
            'received' => $this->received,
            'disbursed' => $this->disbursed,
            'cashInHand' => $this->cashInHand,
        ]);
    }

    public function verifyDailyReport()
    {
        Gate::authorize('verify', $this->report);

        $this->report->verifier = auth()->user()->id;
        $this->report->verified_at = Carbon::now();
        $this->report->status = 'VERIFIED';
        $this->report->save();

        $this->dispatch('daily-report-verified');
        
    }
}
