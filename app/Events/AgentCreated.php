<?php

namespace App\Events;

use App\Models\Agent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AgentCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $agent;

    public function __construct(Agent $agent)
    {
        $this->agent = $agent;
    }
}
