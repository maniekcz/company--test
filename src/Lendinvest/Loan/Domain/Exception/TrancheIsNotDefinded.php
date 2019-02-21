<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Domain\Exception;

class TrancheIsNotDefined extends \Exception
{
    protected $message = "Tranche is not defined";
}