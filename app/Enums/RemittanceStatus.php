<?php

namespace App\Enums;

enum RemittanceStatus: String
{
    case AWAITING_PICKUP = 'AWAITING PICKUP';
    case ACCEPTED = 'ACCEPTED';
    case REJECTED = 'REJECTED';
    case DISBURSED = 'DISBURSED';
}
