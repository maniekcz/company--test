<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Domain;

use Lendinvest\Common\Money;
use Lendinvest\Loan\Domain\Investment\Investment;

class InterestCalculator
{
    /**
     * @var Loan
     */
    private $loan;

    /**
     * @var Investment
     */
    private $investment;

    /**
     * @var string
     */
    private $interest = '0';

    /**
     * @param Loan $loan
     * @param Investment $investment
     * @return Money
     * @throws \Lendinvest\Loan\Domain\Exception\TrancheIsNotDefined
     */
    public function calculate(Loan $loan, Investment $investment): Money
    {
        $this->loan = $loan;
        $this->investment = $investment;
        return $investment->amount()->add($this->calculateCommision());
    }

    /**
     * @return Money
     * @throws Exception\TrancheIsNotDefined
     */
    public function calculateCommision(): Money
    {
        $this->calculatePercent();
        $commision =  bcmul(bcdiv($this->interest, '100', 5), $this->investment->amount()->getAmount(), 2);
        return new Money($commision, $this->investment->amount()->currency());
    }

    /**
     * @return bool
     */
    public function startInTheSameMonth(): bool
    {
        return $this->loan->startDate()->diff($this->investment->created())->m === 0;
    }

    /**
     * @throws Exception\TrancheIsNotDefined
     */
    public function calculatePercent()
    {
        $days = $this->calculateDaysInMonth();
        $interest = $this->loan->getTranche($this->investment->trancheId())->interest();
        if($this->startInTheSameMonth()) {
            $daysNew = $days - ((int) $this->investment->created()->format('j') - 1);
            $interest = bcdiv(bcmul((string) $interest, (string)$daysNew, 4), (string) $days, 4);
        }
        $this->interest = $interest;
    }

    /**
     * @return int
     */
    public function calculateDaysInMonth(): int
    {
        $date = $this->investment->created();
        return cal_days_in_month(CAL_GREGORIAN, (int) $date->format('m'), (int) $date->format('Y'));
    }
}
