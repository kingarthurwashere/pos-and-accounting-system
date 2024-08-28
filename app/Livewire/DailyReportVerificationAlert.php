<?php

namespace App\Livewire;

use App\Models\DailyReport;
use Carbon\Carbon;
use Livewire\Component;

class DailyReportVerificationAlert extends Component
{
    public function render()
    {
        $reportForVerification = DailyReport::whereDate('created_at', Carbon::today())
                ->where('status', 'CONFIRMED')
                ->where('location_id', auth()->user()->login_location_id)
                ->where('assigned_verifier', auth()->user()->id)
                ->first();

        return view('livewire.daily-report-verification-alert', [
            'reportForVerification' => $reportForVerification
        ]);
    }
}
