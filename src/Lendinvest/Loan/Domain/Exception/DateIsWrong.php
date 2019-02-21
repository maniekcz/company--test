<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Domain\Exception;

class DateIsWrong extends \Exception
{
    protected $message = "Date is wrong";
}