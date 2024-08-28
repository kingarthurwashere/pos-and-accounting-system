<?php

namespace App\Enums;

enum RefundStatus: String
{
    case INITIATED = 'INITIATED';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';
    case DISBURSED = 'DISBURSED';
}
