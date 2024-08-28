<?php

namespace App\Listeners;

use App\Events\AgentCreated;
use App\Models\AgentBalance;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateAgentBalance
{
    public function handle(AgentCreated $event)
    {
        $agentBalance = new AgentBalance([
            'agent_id' => $event->agent->id,
            'balance' => 0, // Default balance
        ]);

        $agentBalance->save();
    }
}
