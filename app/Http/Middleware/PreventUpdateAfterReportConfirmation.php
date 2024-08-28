<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\DailyReport;
use Carbon\Carbon;

class PreventUpdateAfterReportConfirmation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Assuming 'confirmed_at' stores the timestamp when the report was confirmed
        $today = Carbon::today();
        $report = DailyReport::whereDate('created_at', $today)->first();

        if ($report && $report->status === 'CONFIRMED') {
            // Optionally, you could log this event or return a specific error response
            return response()->json(['error' => 'Updates are not allowed after the daily report is confirmed.'], 403);
        }

        return $next($request);
    }
}
