<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Location extends Model
{
    use HasFactory;

    public function balance()
    {
        return $this->hasOne(LocationBalance::class);
    }

    public function formattedBalance()
    {
        $amount = $this->balance->usd_balance / 100;
        return number_format($amount, 2);
    }

    public function locationFloatSuffices(int $disburse_amount)
    {
        $amount_after_disbursement = $this->balance->usd_balance - $disburse_amount;
        Log::info('Balance ->' . $this->balance->usd_balance);
        Log::info('Disburse Amount ->' . $disburse_amount);
        Log::info('Amount_after_disbursement ->' . $amount_after_disbursement);

        return ($amount_after_disbursement >= $this->min_allowed_balance);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            //Create balance
            $model->balance()->create();
        });
    }
}
