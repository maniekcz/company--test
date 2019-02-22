<?php

declare(strict_types=1);

namespace Tests\Lendinvest\Loan;

use Lendinvest\Loan\Domain\Exception\InvestorCannotInvest;
use Lendinvest\Loan\Domain\Investor\Investor;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Tests\Lendinvest\Loan\MotherObject\InvestmentMother;
use Tests\Lendinvest\Loan\MotherObject\InvestorMother;

class InvestmentTest extends TestCase
{
    /**
    * @test
    */
    public function when_data_is_correct_then_investment_can_be_create()
    {
        $investment = InvestmentMother::withData(
            '1',
            InvestorMother::withData('2', '1000', 'GBP'),
            '1000',
            'GBP',
            '2012-10-01'
        );
        Assert::assertEquals('1', $investment->id()->toString());
        Assert::assertEquals('1000', $investment->amount()->getAmount());
        Assert::assertEquals(new \DateTimeImmutable('2012-10-01'), $investment->created());
    }

    /**
     * @test
     */
    public function when_investor_has_too_less_money_then_investment_cannot_be_create()
    {
        $this->expectException(InvestorCannotInvest::class);
        InvestmentMother::withData(
            '1',
            InvestorMother::withData('2', '100', 'GBP'),
            '1000',
            'GBP',
            '2012-10-01'
        );

    }
}