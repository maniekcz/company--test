<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Application\Command\Handler;

use Lendinvest\Loan\Application\Command\CalculateInterest;
use Lendinvest\Loan\Domain\Investment\Investment;
use Lendinvest\Loan\Domain\Investor\InvestorId;
use Lendinvest\Loan\Domain\Investor\Investors;
use Lendinvest\Loan\Domain\Loan;
use Lendinvest\Loan\Domain\Loans;
use Tests\Lendinvest\Common\MotherObject\MoneyMother;

class CalculateInterestHandler
{
    /**
     * @var Loans
     */
    private $loans;

    /**
     * @var Investors
     */
    private $investors;

    public function __construct(Loans $loans, Investors $investors)
    {
        $this->loans = $loans;
        $this->investors = $investors;
    }

    public function __invoke(CalculateInterest $command)
    {
        $loans = $this->loans->getByPeriod($command->start(), $command->end());
        /** @var Loan $loan */
        foreach ($loans as $loan) {
            array_map(function (Investment $investment) use ($loan) {
                $invested = $investment->created();
                $loanStartDate = $loan->startDate();
                $interest = $loan->getTranche($investment->trancheId())->interest();
                $days = cal_days_in_month(CAL_GREGORIAN, (int) $invested->format('m'), (int) $invested->format('Y'));
                if (!$loanStartDate->diff($invested)->m) {
                    $daysNew = $days - ((int) $invested->format('j') - 1);
                    $interest = bcdiv(bcmul((string) $interest, (string)$daysNew, 2), (string) $days, 2);
                }
                $total = bcmul(bcdiv((string) $interest, '100', 5), $investment->amount()->getAmount(), 2);
                $investor = $this->investors->get($investment->investorId());
                $investor->increaseBalance(MoneyMother::withAmount($total));
                $this->investors->save($investor);
            }, $loan->investments());
        }
    }
}
