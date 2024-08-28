<?php

namespace App\Models;

use App\Events\WithdrawalRequestDisbursed;
use App\Mail\WithdrawalRequestApproved;
use App\Mail\WithdrawalRequestDisbursed as MailWithdrawalRequestDisbursed;
use App\Mail\WithdrawalRequestRejected;
use App\Models\Traits\PreventsUpdateAfterReportConfirmation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class WithdrawalRequest extends Model
{
    use HasFactory;
    use PreventsUpdateAfterReportConfirmation;

    protected $casts = [
        'approval_datetime' => 'datetime',
        'rejection_datetime' => 'datetime',
        'posted_datetime' => 'datetime',
        'disburse_datetime' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        // Generating a unique reference before creating a new withdrawal request
        static::creating(function ($withdrawalRequest) {
            // Generate the 4 digit unique code
            $uniqueCode = rand(1000, 9999); // Simple example; consider a more unique approach

            // Assuming you want the date part to be dynamic based on the current date
            $datePart = now()->format('ymd.Hi'); // Formats current date and time as DDMMYY.HHMM

            // Set the withdrawal reference
            $withdrawalRequest->reference = "WR{$datePart}.{$uniqueCode}";

            // Ensure uniqueness if necessary, consider querying the database to ensure the generated reference is unique
            while (WithdrawalRequest::where('reference', $withdrawalRequest->reference)->exists()) {
                $uniqueCode = rand(1000, 9999); // Re-generate the 4 digit unique code
                $withdrawalRequest->reference = "WR{$datePart}.{$uniqueCode}";
            }

            //Check if is agent deductible
            $withdrawalRequest->agent_balance_deductible = SELF::isAgentBalanceDeductible($withdrawalRequest->email);
        });

        static::updated(function ($model) {
            if ($model->wasChanged('status')) {
                if ($model->status == 'APPROVED') {

                    try {
                        Mail::to($model->email)->send(new WithdrawalRequestApproved($model));
                    } catch (\Throwable $th) {
                    }
                }

                if ($model->status == 'REJECTED') {

                    try {
                        Mail::to($model->email)->send(new WithdrawalRequestRejected($model));
                    } catch (\Throwable $th) {
                    }
                }

                if ($model->status === 'DISBURSED') {
                    Log::info("LOCATION DISBURSED: {$model->disbursement_location_id}");
                    WithdrawalRequestDisbursed::dispatch($model);
                }
            }
        });
    }

    public static function isAgentBalanceDeductible(string $email): int
    {
        $agent = Agent::whereEmail($email)->first();

        if ($agent) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Set the price attribute.
     *
     * @param  float  $value
     * @return void
     */
    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = $value * 100;
    }

    public function formattedAmount()
    {
        if ($this->amount == 0) {
            return 0;
        }
        $amount = $this->amount / 100;
        return number_format($amount, 2, '.', ',');
    }

    public function scopeSearch($query, $value)
    {
        $query->where("email", "like", "%{$value}%");
    }

    public function requestType()
    {
        return $this->belongsTo(WithdrawalRequestType::class, 'type', 'slug');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function disbursementLocation()
    {
        return $this->belongsTo(Location::class, 'disbursement_location_id', 'id');
    }

    public function disburser()
    {
        return $this->belongsTo(User::class, 'disbursed_by', 'id');
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
