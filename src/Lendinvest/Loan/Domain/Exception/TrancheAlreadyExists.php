<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Domain\Exception;

class TrancheAlreadyExists extends \Exception
{
    protected $message = "Tranche already exists";
}
