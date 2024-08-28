<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Models\Traits\PreventsUpdateAfterReportConfirmation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    use PreventsUpdateAfterReportConfirmation;

    protected $casts = [
        'initition_datetime' => 'datetime',
        'disbursement_datetime' => 'datetime',
        'approval_datetime' => 'datetime',
        'rejection_datetime' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'payable_identifier', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'payable_identifier', 'id');
    }

    // Define an accessor to get the initiated payment
    public function getInitiatedPaymentAttribute()
    {
        return $this->invoices()->whereIn('status', [InvoiceStatus::NOTPAID, InvoiceStatus::DRAFT])->latest()->first();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function paidAmount()
    {
        $amount = $this->total - $this->balance;

        return $amount;
    }

    public function formattedPaidAmount()
    {
        $amount = $this->total - $this->balance;

        if ($amount == 0) {
            return '0.00';
        }

        $amount = $amount / 100;
        return number_format($amount, 2, '.', ',');
    }

    public function formattedTotal()
    {
        $amount = $this->total / 100;
        return number_format($amount, 2, '.', ',');
    }

    public function formattedBalance()
    {
        if ($this->balance == 0) {
            return 0.00;
        } else {
            $amount = $this->balance / 100;
            return number_format($amount, 2, '.', ',');
        }
    }
}
