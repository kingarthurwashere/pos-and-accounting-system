<?php

namespace App\Models;

use App\Events\CityCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function balance()
    {
        return $this->hasOne(CityBalance::class);
    }


    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            CityCreated::dispatch($model);
        });
    }
}
