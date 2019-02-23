<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Domain;

use Lendinvest\Loan\Domain\Investment\Investment;

class InterestCalculator
{
    /**
     * @param Loan $loan
     * @param Investment $investment
     * @return string
     * @throws \Lendinvest\Loan\Domain\Exception\TrancheIsNotDefined
     */
    public function calculate(Loan $loan, Investment $investment): string
    {
        $invested = $investment->created();
        $loanStartDate = $loan->startDate();
        $interest = $loan->getTranche($investment->trancheId())->interest();
        $days = cal_days_in_month(CAL_GREGORIAN, (int) $invested->format('m'), (int) $invested->format('Y'));
        if (!$loanStartDate->diff($invested)->m) {
            $daysNew = $days - ((int) $invested->format('j') - 1);
            $interest = bcdiv(bcmul((string) $interest, (string)$daysNew, 4), (string) $days, 4);
        }
        return bcmul(bcdiv((string) $interest, '100', 5), $investment->amount()->getAmount(), 2);
    }
}
