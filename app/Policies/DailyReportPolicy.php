<?php

namespace App\Policies;

use App\Models\DailyReport;
use App\Models\User;
use Carbon\Carbon;

class DailyReportPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function confirm(User $user, DailyReport $report): bool
    {
        $current_time = Carbon::now();
        $time_to_compare = Carbon::createFromTime(9, 45, 0);

        return $report->status === 'NOT_CONFIRMED' && $user->id === $report->user_id && ($current_time->gt($time_to_compare));
        //return $report->status === 'NOT_CONFIRMED' && $user->id === $report->user_id;
    }

    public function verify(User $user, DailyReport $report): bool
    {
        return $report->status === 'CONFIRMED' && $user->id !== $report->user_id && $user->id === $report->assigned_verifier;
    }

}
