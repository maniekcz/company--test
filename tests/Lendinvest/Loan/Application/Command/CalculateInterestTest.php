<?php

declare(strict_types=1);

namespace Tests\Lendinvest\Loan\Application\Command;

use Lendinvest\Loan\Application\Command\CalculateInterest;
use Lendinvest\Loan\Application\Command\Handler\CalculateInterestHandler;
use Lendinvest\Loan\Domain\Investor\Investors;
use Lendinvest\Loan\Domain\Loans;
use Lendinvest\Loan\Infrastructure\InMemory\InvestorRepository;
use Lendinvest\Loan\Infrastructure\InMemory\LoanRepository;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
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

    public function setUp(): void
    {
        $this->loans = new LoanRepository();
        $this->investors = new InvestorRepository();
        $this->handler = new CalculateInterestHandler($this->loans, $this->investors);
    }

    /**
     * @test
     */
    public function when_loan_and_invester_are_created_then_invester_can_invest()
    {
        $investor = InvestorMother::withData('1', '1000', 'GBP');
        $loan = LoanMother::withData('1', '2012-12-01', '2012-12-12');
        $tranche = TrancheMother::withData('1', 3, '1000', 'GBP');
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

        $command = new CalculateInterest('2012-12-01', '2012-12-12');
        $this->handler->__invoke($command);

        $investor = $this->investors->get($investor->id());
        var_dump($investor->balance());
    }
}
