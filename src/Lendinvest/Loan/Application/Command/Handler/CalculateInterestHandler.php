<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Application\Command\Handler;

use Lendinvest\Loan\Application\Command\CalculateInterest;
use Lendinvest\Loan\Domain\Calculator;
use Lendinvest\Loan\Domain\Investment\Investment;
use Lendinvest\Loan\Domain\Investor\Investors;
use Lendinvest\Loan\Domain\Loan;
use Lendinvest\Loan\Domain\Loans;

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

    /**
     * @var Calculator
     */
    private $calculator;

    public function __construct(Loans $loans, Investors $investors, Calculator $calculator)
    {
        $this->loans = $loans;
        $this->investors = $investors;
        $this->calculator = $calculator;
    }

    public function __invoke(CalculateInterest $command)
    {
        $loans = $this->loans->getByPeriod($command->start(), $command->end());
        /** @var Loan $loan */
        foreach ($loans as $loan) {
            /** @var Investment $investment */
            foreach ($loan->getNewInvestments() as $investment) {
                $investor = $this->investors->get($investment->investorId());
                $interest = $this->calculator->calculate($loan, $investment);
                $investment->close();
                $investor->increaseBalance($interest);
                $this->investors->save($investor);
            }
            $this->loans->save($loan);
        }
    }
}
