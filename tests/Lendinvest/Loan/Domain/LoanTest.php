<?php

declare(strict_types=1);

namespace Tests\Lendinvest\Loan\Domain;

use Lendinvest\Loan\Domain\Exception\CannotOpenLoan;
use Lendinvest\Loan\Domain\Exception\DateIsWrong;
use Lendinvest\Loan\Domain\Exception\InvestorCannotInvest;
use Lendinvest\Loan\Domain\Exception\TrancheAlreadyExists;
use Lendinvest\Loan\Domain\Exception\TrancheIsNotDefined;
use Lendinvest\Loan\Domain\StateLoan;
use Lendinvest\Loan\Domain\Tranche\TrancheId;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Tests\Lendinvest\Common\MotherObject\MoneyMother;
use Tests\Lendinvest\Loan\Domain\MotherObject\InvestmentMother;
use Tests\Lendinvest\Loan\Domain\MotherObject\InvestorMother;
use Tests\Lendinvest\Loan\Domain\MotherObject\LoanMother;
use Tests\Lendinvest\Loan\Domain\MotherObject\TrancheMother;

class LoanTest extends TestCase
{
    /**
     * @test
     */
    public function when_data_is_correct_then_loan_can_be_create()
    {
        $loanId = '1';
        $startDate = '2015-10-01';
        $endDate ='2015-11-15';
        $loan = LoanMother::withData($loanId, $startDate, $endDate);
        Assert::assertEquals($startDate, $loan->startDate()->format('Y-m-d'));
        Assert::assertEquals($endDate, $loan->endDate()->format('Y-m-d'));
        Assert::assertEquals([], $loan->tranches());
        Assert::assertEquals((string) $loanId, (string) $loan->id());
    }

    /**
     * @test
     */
    public function when_dates_are_wrong_then_loan_cannot_be_create()
    {
        $this->expectException(DateIsWrong::class);
        LoanMother::withData('1', '2015-12-1', '2015-11-15');
    }

    /**
     * @test
     */
    public function when_loan_is_created_then_tranche_can_be_add()
    {
        $trancheId = '1';
        $loan = LoanMother::withId('1');
        $tranche = TrancheMother::withId($trancheId);
        $loan->addTranche($tranche);
        Assert::assertTrue($loan->trancheExists(TrancheId::fromString($trancheId)));
    }

    /**
     * @test
     */
    public function when_loan_is_created_and_tranche_is_added_then_the_same_tranche_cannot_be_add()
    {
        $loan = LoanMother::withId('1');
        $tranche =  TrancheMother::withId('1');
        $loan->addTranche($tranche);
        $this->expectException(TrancheAlreadyExists::class);
        $loan->addTranche($tranche);
    }

    /**
     * @test
     */
    public function when_loan_is_created_and_tranche_is_added_then_loan_can_be_open()
    {
        $loan = LoanMother::withId('1');
        $tranche = TrancheMother::withId('1');
        $loan->addTranche($tranche);
        $loan->open();
        Assert::assertEquals(StateLoan::OPEN(), $loan->state());
    }

    /**
     * @test
     */
    public function when_loan_is_created_and_tranche_is_not_added_then_loan_cannot_be_open()
    {
        $loan = LoanMother::withId('1');
        $this->expectException(TrancheIsNotDefined::class);
        $loan->open();
    }

    /**
     * @test
     */
    public function when_loan_is_created_and_tranche_is_not_added_then_tranche_cannot_be_fetch()
    {
        $loan = LoanMother::withId('1');
        $this->expectException(TrancheIsNotDefined::class);
        $loan->getTranche(TrancheId::fromString('1000'));
    }

    /**
     * @test
     */
    public function when_loan_is_created_and_open_and_tranche_is_added_then_loan_cannot_be_open()
    {
        $loan =  LoanMother::withId('1');
        $tranche = TrancheMother::withId('1');
        $loan->addTranche($tranche);
        $loan->open();
        $this->expectException(CannotOpenLoan::class);
        $loan->open();
    }

    /**
     * @test
     */
    public function when_loan_is_open_then_investor_can_invest()
    {
        $loan =  LoanMother::withId('1');
        $trancheId = TrancheId::fromString('1');
        $tranche = TrancheMother::withData($trancheId->toString(), 3, '1000', 'GBP');
        $loan->addTranche($tranche);
        $loan->open();

        $investment = InvestmentMother::withData(
            '1',
            InvestorMother::withData('2', '1000', 'GBP'),
            '1000',
            'GBP',
            '2015-10-03'
        );
        $loan->invest($trancheId, $investment);

        Assert::assertCount(1, $loan->getTranche($trancheId)->investments());
        Assert::assertTrue($loan->getTranche($trancheId)->amount()->equals(MoneyMother::withData('0.00', 'GBP')));
    }

    /**
     * @test
     */
    public function when_loan_is_not_open_then_investor_cannot_invest()
    {
        $loan =  LoanMother::withId('1');
        $trancheId = TrancheId::fromString('1');
        $tranche = TrancheMother::withData($trancheId->toString(), 3, '1000', 'GBP');
        $loan->addTranche($tranche);
        $investment = InvestmentMother::withData(
            '1',
            InvestorMother::withData('2', '1000', 'GBP'),
            '1000',
            'GBP',
            '2015-10-03'
        );

        $this->expectException(InvestorCannotInvest::class);
        $loan->invest($trancheId, $investment);
    }

    /**
     * @test
     */
    public function when_loan_is_open_and_tranche_is_max_invested_then_investor_cannot_invest()
    {
        $loan =  LoanMother::withId('1');
        $trancheId = TrancheId::fromString('1');
        $tranche = $tranche = TrancheMother::withData($trancheId->toString(), 3, '1000', 'GBP');
        $loan->addTranche($tranche);
        $loan->open();
        $investment = InvestmentMother::withData(
            '1',
            InvestorMother::withData('2', '1000', 'GBP'),
            '1000',
            'GBP',
            '2015-10-03'
        );
        $loan->invest($trancheId, $investment);
        $investment = InvestmentMother::withData(
            '1',
            InvestorMother::withData('2', '1000', 'GBP'),
            '1000',
            'GBP',
            '2015-10-03'
        );

        $this->expectException(InvestorCannotInvest::class);
        $loan->invest($trancheId, $investment);
    }
}
