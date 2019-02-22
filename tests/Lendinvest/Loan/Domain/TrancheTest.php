<?php

declare(strict_types=1);

namespace Tests\Lendinvest\Loan\Domain;

use Lendinvest\Common\Currency;
use Lendinvest\Common\Money;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
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
}
