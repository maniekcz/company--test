<?php

declare(strict_types=1);

namespace Tests\Lendinvest\Loan\Application\Command;

use Lendinvest\Common\Money;
use Lendinvest\Loan\Application\Command\CalculateInterest;
use Lendinvest\Loan\Application\Command\Handler\CalculateInterestHandler;
use Lendinvest\Loan\Domain\InterestCalculator;
use Lendinvest\Loan\Domain\Investment\StateInvestment;
use Lendinvest\Loan\Domain\Investor\Investors;
use Lendinvest\Loan\Domain\Loans;
use Lendinvest\Loan\Infrastructure\InMemory\InvestorRepository;
use Lendinvest\Loan\Infrastructure\InMemory\LoanRepository;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Lendinvest\Common\MotherObject\MoneyMother;
use Tests\Lendinvest\Loan\Domain\MotherObject\InvestmentMother;
use Tests\Lendinvest\Loan\Domain\MotherObject\InvestorMother;
use Tests\Lendinvest\Loan\Domain\MotherObject\LoanMother;
use Tests\Lendinvest\Loan\Domain\MotherObject\TrancheMother;

class CalculateInterestTest extends TestCase
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
     * @var CalculateInterestHandler
     */
    private $handler;

    /**
     * @var MockObject | InterestCalculator
     */
    private $calculator;

    public function setUp(): void
    {
        $this->loans = new LoanRepository();
        $this->investors = new InvestorRepository();
        $this->calculator = $this->createMock(InterestCalculator::class);
        $this->handler = new CalculateInterestHandler($this->loans, $this->investors, $this->calculator);
    }

    /**
     * @test
     */
    public function when_investment_is_created_then_interest_can_be_calculate()
    {
        $investor = InvestorMother::withData('1', '1000', 'GBP');
        $loan = LoanMother::withData('1', '2012-12-01', '2012-12-12');
        $tranche = TrancheMother::withData('1', '3', '1000', 'GBP');
        $loan->addTranche($tranche);
        $loan->open();

        $investment = InvestmentMother::withData(
            '1',
            $investor,
            $tranche,
            '1000',
            'GBP',
            '2012-12-03'
        );
        $loan->invest($investment);
        $this->investors->save($investor);
        $this->loans->save($loan);
        $this->calculator->method('calculate')->willReturn(MoneyMother::withData('100', 'GBP'));
        $command = new CalculateInterest('2012-12-01', '2012-12-12');
        $this->handler->__invoke($command);
        Assert::assertTrue($investment->state()->equals(StateInvestment::CLOSED()));
        Assert::assertEquals('1100.00', $investor->balance()->getAmount());
    }

    /**
     * @test
     */
    public function when_investment_is_created_then_interest_can_be_calculate_only_once()
    {
        $investor = InvestorMother::withData('1', '1000', 'GBP');
        $loan = LoanMother::withData('1', '2012-12-01', '2012-12-12');
        $tranche = TrancheMother::withData('1', '3', '1000', 'GBP');
        $loan->addTranche($tranche);
        $loan->open();
        $investment = InvestmentMother::withData(
            '1',
            $investor,
            $tranche,
            '1000',
            'GBP',
            '2012-12-03'
        );
        $loan->invest($investment);
        $this->investors->save($investor);
        $this->loans->save($loan);
        $this->calculator->method('calculate')->willReturn(MoneyMother::withData('100', 'GBP'));
        $command = new CalculateInterest('2012-12-01', '2012-12-12');
        $this->handler->__invoke($command);

        $command = new CalculateInterest('2012-12-01', '2012-12-12');
        $this->handler->__invoke($command);

        Assert::assertTrue($investment->state()->equals(StateInvestment::CLOSED()));
        Assert::assertEquals('1100.00', $investor->balance()->getAmount());
    }
}
