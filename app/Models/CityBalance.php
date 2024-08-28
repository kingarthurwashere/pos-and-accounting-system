<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CityBalance extends Model
{
    use HasFactory;

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function formatted()
    {
        $amount = $this->usd_balance / 100;
        return number_format($amount, 2, '.', ',');
    }
}
