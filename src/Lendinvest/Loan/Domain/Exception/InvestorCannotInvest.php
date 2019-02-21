<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Domain\Exception;

class InvestorCannotInvest extends \Exception
{
    protected $message = "Investor cannot invests";
}
