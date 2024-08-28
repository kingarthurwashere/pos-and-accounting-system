<?php

namespace App\Console\Commands;

use App\Models\DailyReport;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CloseDailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily-report:close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Closes the daily reports for the day.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $reports = DailyReport::where('status', 'VERIFIED')->get();

        foreach ($reports as $r) {
            $ob = $r->location->balance->opening_balance;
            $r->location->balance->opening_balance = $ob + $r->cash_in_hand;
            
            if($r->location->balance->save()) {
                $r->status = 'CLOSED';
                $r->closed_at = Carbon::now();
                $r->save();
            }
        }
    }
}
