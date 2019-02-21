<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Domain\Exception;

class CannotOpenLoan extends \Exception
{
    protected $message = "Cannot opens loan";
}