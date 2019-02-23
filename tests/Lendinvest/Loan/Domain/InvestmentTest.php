<?php

declare(strict_types=1);

namespace Tests\Lendinvest\Loan\Domain;

use Lendinvest\Common\Currency;
use Lendinvest\Common\Money;
use Lendinvest\Loan\Domain\Exception\InvestmentCannotBeClosed;
use Lendinvest\Loan\Domain\Exception\InvestorCannotInvest;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Tests\Lendinvest\Loan\Domain\MotherObject\InvestmentMother;
use Tests\Lendinvest\Loan\Domain\MotherObject\InvestorMother;
use Tests\Lendinvest\Loan\Domain\MotherObject\TrancheMother;

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
            TrancheMother::withId('1'),
            '1000',
            'GBP',
            '2012-10-01'
        );
        Assert::assertEquals('1', $investment->id()->toString());
        Assert::assertTrue($investment->amount()->equals(new Money('1000', new Currency('GBP'))));
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
            TrancheMother::withId('1'),
            '1000',
            'GBP',
            '2012-10-01'
        );
    }

    /**
     * @test
     */
    public function when_investment_is_closed_then_investment_cannot_be_close()
    {
        $investment = InvestmentMother::withData(
            '1',
            InvestorMother::withData('2', '1000', 'GBP'),
            TrancheMother::withId('1'),
            '1000',
            'GBP',
            '2012-10-01'
        );
        $investment->close();
        $this->expectException(InvestmentCannotBeClosed::class);
        $investment->close();
    }
}
