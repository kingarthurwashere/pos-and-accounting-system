<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundType extends Model
{
    use HasFactory;

    public function refunds()
    {
        return $this->hasMany(Refund::class, 'type', 'slug');
    }
}
