<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentSource extends Model
{
    public function payment()
    {
        return $this->HasMany(Payment::class, 'source', 'slug');
    }
}
