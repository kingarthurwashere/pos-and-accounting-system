<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Log;
use App\Models\DailyReport;
use App\Exceptions\RedirectException;

trait PreventsUpdateAfterReportConfirmation
{
    protected static function bootPreventsUpdateAfterReportConfirmation()
    {
        static::creating(function ($model) {
            if(auth()->user()) {
                $report = DailyReport::whereDate('created_at', Carbon::today())
                ->where('status', 'CONFIRMED')
                ->where('location_id', auth()->user()->login_location_id)
                ->first();

                if ($report) {
                    Log::info('Attempt to create model after daily report confirmation was blocked.');
                    
                    throw new RedirectException('Action not allowed as the daily report has been confirmed.');
                }
            }
            
        });
        
        static::updating(function ($model) {

            if(auth()->user()) {
                $report = DailyReport::whereDate('created_at', Carbon::today())
                    ->where('status', 'CONFIRMED')
                    ->where('location_id', auth()->user()->login_location_id)
                    ->first();

                if ($report) {
                    Log::info('Attempt to update model after daily report confirmation was blocked.');
                    
                    throw new RedirectException('Action not allowed as the daily report has been confirmed.');
                }
            }
            
        });
    }
}
