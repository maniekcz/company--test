<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Domain\Exception;

class InvestmentAlreadyExists extends \Exception
{
    protected $message = "Investment already exists";
}
