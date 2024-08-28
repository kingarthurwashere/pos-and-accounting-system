<?php

namespace App\Models;

use App\Events\RefundDisbursed;
use App\Models\Traits\PreventsUpdateAfterReportConfirmation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;
    use PreventsUpdateAfterReportConfirmation;

    protected $casts = [
        'initition_datetime' => 'datetime',
        'disbursement_datetime' => 'datetime',
        'approval_datetime' => 'datetime',
        'rejection_datetime' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::updated(function ($model) {
            if ($model->wasChanged('status')) {
                if ($model->status === 'DISBURSED') {
                    RefundDisbursed::dispatch($model);
                }
            }
        });
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    public function type()
    {
        return $this->hasOne(RefundType::class, 'slug', 'type');
    }

    public function formattedAmount()
    {
        $amount = $this->amount / 100;
        return number_format($amount, 2, '.', '');
    }

    public function initiator()
    {
        return $this->belongsTo(User::class, 'initiated_by', 'id');
    }

    public function method()
    {
        return $this->belongsTo(RefundMethod::class, 'refund_method', 'slug');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function disburser()
    {
        return $this->belongsTo(User::class, 'disbursed_by', 'id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
