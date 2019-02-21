<?php

declare(strict_types=1);

namespace Tests\Lendinvest\Loan;

use Lendinvest\Common\Currency;
use Lendinvest\Common\Money;
use Lendinvest\Loan\Domain\Tranche\Tranche;
use Lendinvest\Loan\Domain\Tranche\TrancheId;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class TrancheTest extends TestCase
{
    /**
     * @test
     */
    public function when_data_is_correct_then_tranche_can_be_create()
    {
        $trancheId = TrancheId::fromString('1');
        $interest = 3;
        $money =  new Money('100', new Currency('GBP'));
        $tranche = Tranche::create(
            $trancheId,
            $interest,
            $money
        );
        Assert::assertEquals($trancheId, $tranche->id());
        Assert::assertEquals($interest, $tranche->interest());
        Assert::assertEquals($money, $tranche->amount());
        Assert::assertEquals((string) $trancheId, (string) $tranche->id());
    }
}
