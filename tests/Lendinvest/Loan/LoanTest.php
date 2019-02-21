<?php

declare(strict_types=1);

namespace Tests\Lendinvest\Loan;

use DateTimeImmutable;
use Lendinvest\Common\Currency;
use Lendinvest\Common\Money;
use Lendinvest\Loan\Domain\Exception\CannotOpenLoan;
use Lendinvest\Loan\Domain\Exception\DateIsWrong;
use Lendinvest\Loan\Domain\Exception\TrancheAlreadyExists;
use Lendinvest\Loan\Domain\Exception\TrancheIsNotDefined;
use Lendinvest\Loan\Domain\Loan;
use Lendinvest\Loan\Domain\LoanId;
use Lendinvest\Loan\Domain\StateLoan;
use Lendinvest\Loan\Domain\Tranche\Tranche;
use Lendinvest\Loan\Domain\Tranche\TrancheId;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class LoanTest extends TestCase
{
    /**
     * @test
     */
    public function when_data_is_correct_then_loan_can_be_create()
    {
        $loanId = LoanId::fromString('1');
        $startDate =  new DateTimeImmutable('2015-10-1');
        $endDate = new DateTimeImmutable('2015-11-15');
        $loan = Loan::create(
            $loanId,
            $startDate,
            $endDate
        );
        Assert::assertEquals($loanId, $loan->id());
        Assert::assertEquals($startDate, $loan->startDate());
        Assert::assertEquals($endDate, $loan->endDate());
        Assert::assertEquals([], $loan->tranches());
        Assert::assertEquals((string) $loanId, (string) $loan->id());
    }

    /**
     * @test
     */
    public function when_dates_are_wrong_then_loan_cannot_be_create()
    {
        $this->expectException(DateIsWrong::class);
        Loan::create(
            LoanId::fromString('1'),
            new DateTimeImmutable(' 2015-12-1'),
            new DateTimeImmutable('2015-11-15')
        );
    }

    /**
     * @test
     */
    public function when_loan_is_created_then_tranche_can_be_add()
    {
        $trancheId = TrancheId::fromString('1');
        $loan = Loan::create(
            LoanId::fromString('1'),
            new DateTimeImmutable(' 2015-10-1'),
            new DateTimeImmutable('2015-11-15')
        );
        $tranche = Tranche::create($trancheId, 3, new Money('100', new Currency('GBP')));
        $loan->addTranche($tranche);
        Assert::assertTrue($loan->trancheExists($trancheId));
    }

    /**
     * @test
     */
    public function when_loan_is_created_and_tranche_is_added_then_the_same_tranche_cannot_be_add()
    {
        $trancheId = TrancheId::fromString('1');
        $loan = Loan::create(
            LoanId::fromString('1'),
            new DateTimeImmutable(' 2015-10-1'),
            new DateTimeImmutable('2015-11-15')
        );
        $tranche = Tranche::create($trancheId, 3, new Money('100', new Currency('GBP')));
        $loan->addTranche($tranche);
        $this->expectException(TrancheAlreadyExists::class);
        $loan->addTranche($tranche);
    }

    /**
     * @test
     */
    public function when_loan_is_created_and_tranche_is_added_then_loan_can_be_open()
    {
        $trancheId = TrancheId::fromString('1');
        $loan = Loan::create(
            LoanId::fromString('1'),
            new DateTimeImmutable(' 2015-10-1'),
            new DateTimeImmutable('2015-11-15')
        );
        $tranche = Tranche::create($trancheId, 3, new Money('100', new Currency('GBP')));
        $loan->addTranche($tranche);
        $loan->open();
        Assert::assertEquals(StateLoan::OPEN(), $loan->state());
    }

    /**
     * @test
     */
    public function when_loan_is_created_and_tranche_is_not_added_then_loan_cannot_be_open()
    {
        $loan = Loan::create(
            LoanId::fromString('1'),
            new DateTimeImmutable(' 2015-10-1'),
            new DateTimeImmutable('2015-11-15')
        );
        $this->expectException(TrancheIsNotDefined::class);
        $loan->open();
    }

    /**
     * @test
     */
    public function when_loan_is_created_and_open_and_tranche_is_added_then_loan_cannot_be_open()
    {
        $trancheId = TrancheId::fromString('1');
        $loan = Loan::create(
            LoanId::fromString('1'),
            new DateTimeImmutable(' 2015-10-1'),
            new DateTimeImmutable('2015-11-15')
        );
        $tranche = Tranche::create($trancheId, 3, new Money('100', new Currency('GBP')));
        $loan->addTranche($tranche);
        $loan->open();
        $this->expectException(CannotOpenLoan::class);
        $loan->open();
    }
}
