<?php

namespace App\Events;

use App\Models\WithdrawalRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WithdrawalRequestDisbursed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public WithdrawalRequest $withdrawalRequest)
    {
        //
    }
}
