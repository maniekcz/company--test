<?php

declare(strict_types=1);

namespace Tests\Lendinvest\Loan\Domain\MotherObject;

use Lendinvest\Loan\Domain\Investment\Investment;
use Lendinvest\Loan\Domain\Investment\InvestmentId;
use Lendinvest\Loan\Domain\Investor\Investor;
use Lendinvest\Loan\Domain\Tranche\Tranche;
use Tests\Lendinvest\Common\MotherObject\MoneyMother;

class InvestmentMother
{
    /**
     * @param string $id
     * @return Investment
     * @throws \Lendinvest\Loan\Domain\Exception\InvestorCannotInvest
     */
    public static function withId(string $id) : Investment
    {
        return Investment::create(
            InvestmentId::fromString($id),
            InvestorMother::withId('1'),
            TrancheMother::withId('1'),
            MoneyMother::correct(),
            new \DateTimeImmutable()
        );
    }

    /**
     * @param string $id
     * @param Investor $investor
     * @param Tranche $tranche
     * @param string $amount
     * @param string $currency
     * @param string $date
     * @return Investment
     * @throws \Lendinvest\Loan\Domain\Exception\InvestorCannotInvest
     */
    public static function withData(
        string $id,
        Investor $investor,
        Tranche $tranche,
        string $amount,
        string $currency,
        string $date
    ) : Investment {
        return Investment::create(
            InvestmentId::fromString($id),
            $investor,
            $tranche,
            MoneyMother::withData($amount, $currency),
            new \DateTimeImmutable($date)
        );
    }
}
