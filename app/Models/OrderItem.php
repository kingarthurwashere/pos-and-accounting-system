<?php

namespace App\Models;

use App\Models\Traits\PreventsUpdateAfterReportConfirmation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    use PreventsUpdateAfterReportConfirmation;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function formattedPrice()
    {
        $amount = $this->price / 100;
        return number_format($amount, 2, '.', ',');
    }

    public function formattedTotal()
    {
        $amount = $this->total / 100;
        return number_format($amount, 2, '.', ',');
    }
}
