<?php

declare(strict_types=1);

namespace App\Enums;
use BenSampo\Enum\Enum;
final class OrderStatus extends Enum
{
    const PENDING = 1;
    const CONFIRM = 2;
    const WAITING_FOR_DELIVERING = 3;
    const DELIVERING = 4;
    const DELIVERED = 5;
    const FAILED = 6;
    const REFUND = 7;
    const CANCEL = 8;
    const SUCCESS = 9;
}
