<?php

namespace App\Enums;

enum DailyReportStatus: String
{
    case NOT_CONFIRMED = 'NOT_CONFIRMMED';
    case CONFIRMED = 'CONFIRMMED';
    case VERIFIED = 'VERIFIED';
    case CLOSED = 'CLOSED';

}
