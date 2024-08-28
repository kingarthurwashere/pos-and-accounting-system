<?php

namespace App\Models;

use App\Models\Traits\PreventsUpdateAfterReportConfirmation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    use PreventsUpdateAfterReportConfirmation;

    protected $table = 'cart';

    public function product()
    {
        return $this->hasOne(StockProduct::class, 'id', 'product_id'); // Replace 'CartItem' with the actual model name if different
    }

    public function getTotal()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }
    public function getFormattedTotal()
    {
        if ($this->getTotal() == 0) {
            return '0.00';
        } else {
            return number_format($this->getTotal() / 100, 2);
        }
    }

    public function getQuantity()
    {
        return $this->items->sum('quantity');
    }
}
