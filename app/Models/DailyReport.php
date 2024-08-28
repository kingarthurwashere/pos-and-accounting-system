<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    use HasFactory;

    protected $casts = [
        'verified_at' => 'datetime',
        'closed_at' => 'datetime',
        'id' => 'string',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verifier_id', 'id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function formattedOB()
    {
        $amount = $this->opening_balance / 100;
        return number_format($amount, 2, '.', ',');
    }

    public function formattedCB()
    {
        $amount = $this->closing_balance / 100;
        return number_format($amount, 2, '.', ',');
    }

    public function formattedReceived()
    {
        $amount = $this->total_received / 100;
        return number_format($amount, 2, '.', ',');
    }

    public function formattedDisbursed()
    {
        $amount = $this->total_disbursed / 100;
        return number_format($amount, 2, '.', ',');
    }
}
