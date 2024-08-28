<?php

namespace App\Listeners;

use App\Events\CityCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateCityBalance
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CityCreated $event): void
    {
        $event->city->balance()->create();
    }
}
