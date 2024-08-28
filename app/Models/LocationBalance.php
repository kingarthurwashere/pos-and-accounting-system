<?php

namespace App\Models;

use App\Events\LocationBalanceUpdated;
use App\Models\Traits\PreventsUpdateAfterReportConfirmation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class LocationBalance extends Model
{
    use HasFactory;
    use PreventsUpdateAfterReportConfirmation;

    public static function boot()
    {
        parent::boot();

        static::updated(function ($model) {
            if ($model->wasChanged('usd_balance')) {
                LocationBalanceUpdated::dispatch($model);
            } else {
                Log::info("Balance NOT changed for {$model->location_id}");
            }
        });
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
