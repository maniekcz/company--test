<?php

declare(strict_types=1);

namespace Tests\Lendinvest\Loan\MotherObject;

use Lendinvest\Loan\Domain\Investor\Investor;
use Lendinvest\Loan\Domain\Investor\InvestorId;
use Tests\Lendinvest\Common\MotherObject\MoneyMother;

class InvestorMother
{
    /**
     * @param string $id
     * @return Investor
     */
    public static function withId(string $id) : Investor
    {
        return Investor::create(
            InvestorId::fromString($id),
            MoneyMother::correct()
        );
    }

    /**
     * @param string $id
     * @param string $amount
     * @param string $currency
     * @return Investor
     */
    public static function withData(string $id, string $amount, string $currency) : Investor
    {
        return Investor::create(
            InvestorId::fromString($id),
            MoneyMother::withData($amount, $currency)
        );
    }
}