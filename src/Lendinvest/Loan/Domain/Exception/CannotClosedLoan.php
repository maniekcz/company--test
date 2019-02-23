<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Domain\Exception;

class CannotClosedLoan extends \Exception
{
    protected $message = "Cannot closed loan";
}
