<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StockProduct extends Model
{
    use HasFactory;

    protected $table = 'stock_products';
    protected $guarded = [];

    /**
     * Set the price attribute.
     *
     * @param  float  $value
     * @return void
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value * 100;
    }

    public function formattedPrice()
    {
        $amount = $this->price / 100;
        return number_format($amount, 2, '.', ',');
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'id', 'uploaded_by');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
