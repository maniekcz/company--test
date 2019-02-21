<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Domain;

use Lendinvest\Common\Enum;

/**
 * @method static OPEN()
 * @method static NEW()
 * @method static CLOSED()
 */
class StateLoan extends Enum
{
    const OPEN = "OPEN";
    const CLOSED = "CLOSED";
    const NEW = "NEW";
}
