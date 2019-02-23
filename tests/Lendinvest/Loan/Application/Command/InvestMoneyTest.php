<?php

declare(strict_types=1);

namespace Tests\Lendinvest\Loan\Application\Command;

use Lendinvest\Common\Currency;
use Lendinvest\Common\Money;
use Lendinvest\Loan\Application\Command\Handler\InvestMoneyHandler;
use Lendinvest\Loan\Application\Command\InvestMoney;
use Lendinvest\Loan\Domain\Investor\InvestorId;
use Lendinvest\Loan\Domain\Investor\Investors;
use Lendinvest\Loan\Domain\LoanId;
use Lendinvest\Loan\Domain\Loans;
use Lendinvest\Loan\Domain\Tranche\TrancheId;
use Lendinvest\Loan\Infrastructure\InMemory\InvestorRepository;
use Lendinvest\Loan\Infrastructure\InMemory\LoanRepository;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Tests\Lendinvest\Loan\Domain\MotherObject\InvestorMother;
use Tests\Lendinvest\Loan\Domain\MotherObject\LoanMother;
use Tests\Lendinvest\Loan\Domain\MotherObject\TrancheMother;

class InvestMoneyTest extends TestCase
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
     * @var InvestMoneyHandler
     */
    private $handler;

    public function setUp(): void
    {
        $this->loans = new LoanRepository();
        $this->investors = new InvestorRepository();
        $this->handler = new InvestMoneyHandler($this->loans, $this->investors);
    }

    /**
     * @test
     */
    public function when_loan_and_investor_are_created_then_investor_can_invest()
    {
        $investor = InvestorMother::withData('1', '1000', 'GBP');
        $loan = LoanMother::withData('1', '2012-12-12', '2012-12-12');
        $tranche = TrancheMother::withData('1', '3', '1000', 'GBP');
        $loan->addTranche($tranche);
        $loan->open();

        $this->investors->save($investor);
        $this->loans->save($loan);

        $command = new InvestMoney('1', '1000', 'GBP', '2012-12-12', '1', '1', '1');
        $this->handler->__invoke($command);

        $investor = $this->investors->get(InvestorId::fromString('1'));
        $loan = $this->loans->get(LoanId::fromString('1'));
        $tranche = $loan->getTranche(TrancheId::fromString('1'));

        Assert::assertTrue($tranche->amount()->isZero());
        Assert::assertTrue($investor->balance()->isZero());
    }

    /**
     * @test
     */
    public function when_loan_not_exists_then_investor_cannot_invest()
    {
        $this->expectException(\Exception::class);
        $command = new InvestMoney('1', '1000', 'GBP', '2012-12-12', '1', '1', '1');
        $this->handler->__invoke($command);
    }

    /**
     * @test
     */
    public function when_investor_not_exists_then_investor_cannot_invest()
    {
        $loan = LoanMother::withData('1', '2012-12-12', '2012-12-12');
        $tranche = TrancheMother::withData('1', '3', '1000', 'GBP');
        $loan->addTranche($tranche);
        $loan->open();
        $this->loans->save($loan);

        $this->expectException(\Exception::class);
        $command = new InvestMoney('1', '1000', 'GBP', '2012-12-12', '1', '1', '1');
        $this->handler->__invoke($command);
    }
}
