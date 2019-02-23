<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Domain\Exception;

class InvestmentCannotBeClosed extends \Exception
{
    protected $message = "Investment cannot be closed";
}
