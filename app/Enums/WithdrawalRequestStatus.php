<?php

namespace App\Enums;

enum WithdrawalRequestStatus: String
{
    case PENDING = 'PENDING';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';
    case DISBURSED = 'DISBURSED';
}
