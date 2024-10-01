<?php
declare(strict_types=1);

namespace App\Enums;

enum ReminderIntervals: string
{
    case ONE_DAY = '1D';
    case TWO_DAYS = '2D';
    case FOUR_DAYS = '4D';
    case ONE_WEEK = '1W';
    case TWO_WEEKS = '2W';
}
