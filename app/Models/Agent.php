<?php

namespace App\Models;

use App\Events\AgentCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    public function balance()
    {
        return $this->hasOne(AgentBalance::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            AgentCreated::dispatch($model);
        });
    }
}
