<?php

namespace App\Enums;

enum InvoiceStatus: String
{
    case DRAFT = 'DRAFT';
    case DISPUTED = 'DISPUTED';
    case PAID = 'PAID';
    case NOTPAID = 'NOT_PAID';
}
