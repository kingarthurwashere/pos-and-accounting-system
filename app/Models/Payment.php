<?php

namespace App\Models;

use App\Events\OrderPaymentReceived;
use App\Events\PaymentReceived;
use App\Events\RemittanceFloatDeposited;
use App\Mail\PaymentReceiptEmail;
use App\Mail\PaymentReceived as MailPaymentReceived;
use App\Mail\RemittanceReceiptEmail;
use App\Models\Traits\PreventsUpdateAfterReportConfirmation;
use App\Notifications\PaymentMade;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class Payment extends Model
{
    use HasFactory;
    use PreventsUpdateAfterReportConfirmation;

    protected $casts = [
        'received_amount_datetime' => 'datetime',
        'refund_posted_datetime' => 'datetime',
        'posted_datetime' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            Log::info('Payment created', ['model' => $model->toArray()]); // Changed for better logging
            // Directly check if 'receiving_cashier' is set
            if (!is_null($model->receiving_cashier)) { // Checks if 'receiving_cashier' is set upon creation
                if ($model->payable_slug === 'order') {
                    OrderPaymentReceived::dispatch($model);

                    if($model->order->customer_email){
                        Mail::to($model->order->customer_email)->send(new PaymentReceiptEmail($model));
                    }
                } 

                try {
                    $notificationEmail = env('ADMIN_EMAIL');
                    Mail::to($notificationEmail)->send(new MailPaymentReceived($model));
                    
                } catch (\Throwable $th) {
                    dd($th->getMessage());
                }

            } else {
                Log::info('receiving_cashier WASNT SET'); // Changed from Log::error for proper semantics
            }

            if ($model->payable_slug === 'remittance') {
                Log::info('Payment Slug was remittance');
                RemittanceFloatDeposited::dispatch(Remittance::find($model->payable_identifier));
            }
        });
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function setTenderAttribute($value)
    {
        Log::info("Setting tender value");
        $this->attributes['tender'] = $value * 100;
    }

    public function setReceivedAmountAttribute($value)
    {
        Log::info("Setting received amount value");
        $this->attributes['received_amount'] = $value * 100;
    }

    public function formatted()
    {
        return number_format(($this->received_amount / 100), 2);
    }

    public function formattedReceivedAmount()
    {
        if ($this->received_amount == 0) {
            return '0.00'; // Or any default format you prefer for zero value
        }
        $amount = $this->received_amount / 100;
        return number_format($amount, 2, '.', '');
    }

    public function formattedChangeAmount()
    {
        if ($this->change_amount == 0) {
            return '0.00'; // Or any default format you prefer for zero value
        }
        $amount = $this->change_amount / 100;
        return number_format($amount, 2, '.', '');
    }

    public function formattedTenderAmount()
    {
        if ($this->tender == 0) {
            return '0.00'; // Or any default format you prefer for zero value
        }
        $amount = $this->tender / 100;
        return number_format($amount, 2, '.', '');
    }


    public function payable()
    {
        return $this->hasOne(Payable::class, 'slug', 'payable_slug');
    }

    public function refundInitiated()
    {
        return $this->hasOne(Refund::class, 'payment_id', 'id')->where('status', 'INITIATED');
    }

    public function refundApproved()
    {
        return $this->hasOne(Refund::class, 'payment_id', 'id')->where('status', 'Approved');
    }

    public function remittance()
    {
        return $this->belongsTo(Remittance::class, 'payable_identifier', 'id');
    }

    public function refundRejected()
    {
        return $this->hasOne(Refund::class, 'payment_id', 'id')->where('status', 'Rejected');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'payable_identifier', 'id');
    }

    public function refund()
    {
        return $this->hasOne(Refund::class);
    }

    public function initialiser()
    {
        return $this->belongsTo(User::class, 'initiated_by', 'id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'receiving_cashier', 'id');
    }

    public function paymentSource()
    {
        return $this->belongsTo(PaymentSource::class, 'source', 'slug');
    }
}
