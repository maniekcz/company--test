<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Domain\Investment;

use Lendinvest\Common\Enum;

/**
 * @method static NEW()
 * @method static CLOSED()
 */
class StateInvestment extends Enum
{
    const CLOSED = "CLOSED";
    const NEW = "NEW";
}
