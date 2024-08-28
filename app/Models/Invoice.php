<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    public function formattedAmountDue()
    {
        if (is_null($this->amount_due)) {
            return '0.00';
        } else {
            $amount = $this->amount_due / 100;
            return number_format($amount, 2, '.', ''); // Format to 2 decimal places as a string
        }
    }
    public function payable()
    {
        return $this->hasOne(Payable::class, 'slug', 'payable_slug');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function scopeNotPaid($query)
    {
        return $query->where('status', InvoiceStatus::NOTPAID);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'payable_identifier', 'id');
    }

    public function initialiser()
    {
        return $this->belongsTo(User::class, 'initiated_by', 'id');
    }
}
