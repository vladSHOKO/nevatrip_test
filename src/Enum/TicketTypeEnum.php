<?php

namespace App\Enum;

enum TicketTypeEnum: string
{
    case ADULT = 'adult';
    case KID = 'kid';
    case GROUP = 'group';
    case DISCOUNTED = 'discounted';
}
