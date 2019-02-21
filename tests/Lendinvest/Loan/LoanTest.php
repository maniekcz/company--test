<?php

declare(strict_types=1);

namespace Tests\Lendinvest\Loan;

use DateTimeImmutable;
use Lendinvest\Loan\Domain\Exception\DateIsWrong;
use Lendinvest\Loan\Domain\Loan;
use Lendinvest\Loan\Domain\LoanId;
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
        $loan = Loan::create(
            $loanId,
            new DateTimeImmutable('2015-10-1'),
            new DateTimeImmutable('2015-11-15')
        );
        Assert::assertEquals($loanId, $loan->id());
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
            new \DateTimeImmutable(' 2015-10-1'),
            new \DateTimeImmutable('2015-11-15')
        );
        $tranche = Tranche::create($trancheId, 3, new Money('100', new Currency('GBP')));
        $loan->addTranche($tranche);
        Assert::assertTrue($loan->trancheExists($trancheId));
    }

}
