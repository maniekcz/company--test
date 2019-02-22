<?php

declare(strict_types=1);

namespace Tests\Lendinvest\Loan\Domain;

use Lendinvest\Common\Currency;
use Lendinvest\Common\Money;
use Lendinvest\Loan\Domain\Exception\InvestmentAlreadyExists;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Tests\Lendinvest\Loan\Domain\MotherObject\InvestmentMother;
use Tests\Lendinvest\Loan\Domain\MotherObject\InvestorMother;
use Tests\Lendinvest\Loan\Domain\MotherObject\TrancheMother;

class TrancheTest extends TestCase
{
    /**
     * @test
     */
    public function when_data_is_correct_then_tranche_can_be_create()
    {
        $trancheId = '1';
        $interest = 3;
        $amount = '100';
        $tranche = TrancheMother::withData($trancheId, $interest, $amount, 'GBP');
        Assert::assertEquals($interest, $tranche->interest());
        Assert::assertTrue($tranche->amount()->equals(new Money($amount, new Currency('GBP'))));
        Assert::assertEquals((string) $trancheId, (string) $tranche->id());
    }

    /**
     * @test
     */
    public function when_tranche_is_invested_then_the_same_investment_cannot_be_added()
    {
        $tranche = TrancheMother::withData('1', 3, '1000', 'GBP');;
        $investment = InvestmentMother::withData(
            '2',
            InvestorMother::withData('3', '1000', 'GBP'),
            '100',
            'GBP',
            '2019-01-01'
        );
        $tranche->invest($investment);
        $this->expectException(InvestmentAlreadyExists::class);
        $tranche->invest($investment);
    }
}
