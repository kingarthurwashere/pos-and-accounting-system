<?php

namespace App\Models;

use App\Models\Traits\PreventsUpdateAfterReportConfirmation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentEarning extends Model
{
    use HasFactory;
    use PreventsUpdateAfterReportConfirmation;

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
