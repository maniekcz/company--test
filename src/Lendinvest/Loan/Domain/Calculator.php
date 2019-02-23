<?php
declare(strict_types=1);

namespace Lendinvest\Loan\Domain;

use Lendinvest\Common\Money;
use Lendinvest\Loan\Domain\Investment\Investment;

interface Calculator
{
    public function calculate(Loan $loan, Investment $investment): Money;
}
