<?php

namespace App\Models;

use App\Models\Traits\PreventsUpdateAfterReportConfirmation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\RemittanceDisbursed;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Remittance extends Model
{
    use HasFactory;
    use PreventsUpdateAfterReportConfirmation;

    protected $casts = [
        'due_at' => 'datetime',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'disbursed_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::updated(function ($model) {
            if ($model->wasChanged('status')) {
                if ($model->status === 'DISBURSED') {
                    RemittanceDisbursed::dispatch($model);
                }
            }

            if ($model->wasChanged('accepted_by')) {
                if ($model->status === 'ACCEPTED') {
                    try {
                        $payment = Payment::create([
                            'payable_identifier' => $model->id,
                            'covers_full_balance' => 1,
                            'source' => 'AGENTX-CASH',
                            'tender' => $model->amount / 100,
                            'received_amount' => $model->amount / 100,
                            'received_amount_datetime' => $model->created_at,
                            'posted_datetime' => $model->created_at,
                            'initiated_by' => 1,
                            'posted' => 0,
                            'change_amount' => 0,
                            'payable_slug' => 'remittance',
                            'status' => 'RECEIVED',
                            'receiving_cashier' => $model->accepted_by,
                            'location_id' => $model->funded_location_id,
                        ]);
                        Log::info('Payment CREATED');
                        Log::info($payment);
                    } catch (\Throwable $th) {
                        Log::info('Payment NOT CREATED');
                        Log::info($th->getMessage());
                    }
                }
            }
        });

        

        
        
    }

    public function formattedAmount()
    {
        $amount = $this->amount / 100;
        return number_format($amount, 2, '.', '');
    }

    public function formattedReceivable()
    {
        $amount = $this->receivable / 100;
        return number_format($amount, 2, '.', '');
    }

    public function acceptor()
    {
        return $this->belongsTo(User::class, 'accepted_by', 'id');
    }

    public function disbursementLocation()
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function disburser()
    {
        return $this->belongsTo(User::class, 'disbursed_by', 'id');
    }

    public function fundedCity()
    {
        return $this->belongsTo(City::class, 'funded_city', 'slug');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'payable_identifier', 'id');
    }

    public function rejecter()
    {
        return $this->belongsTo(User::class, 'rejected_by', 'id');
    }

    public function method()
    {
        return $this->belongsTo(WithdrawalMethod::class, 'withdrawal_method_slug', 'slug');
    }
}
