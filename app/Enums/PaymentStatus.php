<?php

namespace App\Enums;

enum PaymentStatus: String
{
    case RECEIVED = 'RECEIVED';
    case REFUNDED = 'REFUNDED';

    public function getColor(): string
    {
        return match ($this) {
            self::RECEIVED => 'success',
            self::REFUNDED => 'danger'
        };
    }
}
