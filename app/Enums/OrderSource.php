<?php

namespace App\Enums;

enum OrderSource: String
{
    case ONLINE = 'ONLINE';
    case INVENTORY = 'INVENTORY';
}
