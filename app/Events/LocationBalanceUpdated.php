<?php

namespace App\Events;

use App\Models\Location;
use App\Models\LocationBalance;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LocationBalanceUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public LocationBalance $balance)
    {
        //
    }
}
