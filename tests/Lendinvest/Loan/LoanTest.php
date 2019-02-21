<?php

declare(strict_types=1);

namespace Tests\Lendinvest\Loan;

use DateTimeImmutable;
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
}
