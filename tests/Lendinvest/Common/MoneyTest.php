<?php

declare(strict_types=1);

namespace Tests\Lendinvest\Common;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Tests\Lendinvest\Common\MotherObject\MoneyMother;

class MoneyTest extends TestCase
{
    /**
     * @test
     */
    public function test_comparison_grater_then()
    {
        $money = MoneyMother::withData('1000', 'GBP');
        $money1 = MoneyMother::withData('100', 'GBP');
        Assert::assertTrue($money->greaterThan($money1));
        Assert::assertFalse($money1->greaterThan($money));
    }

    /**
     * @test
     */
    public function test_comparison_grater_then_or_equal()
    {
        $money = MoneyMother::withData('1000', 'GBP');
        $money1 = MoneyMother::withData('100', 'GBP');
        $money2 = MoneyMother::withData('100', 'GBP');
        Assert::assertTrue($money->greaterThanOrEqual($money1));
        Assert::assertTrue($money->greaterThanOrEqual($money2));
        Assert::assertFalse($money1->greaterThanOrEqual($money));
    }

    /**
     * @test
     */
    public function test_comparison_less_than()
    {
        $money = MoneyMother::withData('100', 'GBP');
        $money1 = MoneyMother::withData('1000', 'GBP');
        Assert::assertTrue($money->lessThan($money1));
        Assert::assertFalse($money1->lessThan($money));
    }

    /**
     * @test
     */
    public function test_comparison_less_than_or_equal()
    {
        $money = MoneyMother::withData('100', 'GBP');
        $money1 = MoneyMother::withData('1000', 'GBP');
        $money2 = MoneyMother::withData('100', 'GBP');
        Assert::assertTrue($money->lessThanOrEqual($money1));
        Assert::assertTrue($money->lessThanOrEqual($money2));
        Assert::assertFalse($money1->lessThanOrEqual($money));
    }

    /**
     * @test
     */
    public function test_comparison_with_wrong_currency()
    {
        $money = MoneyMother::withData('100', 'GBP');
        $money1 = MoneyMother::withData('1000', 'PLN');
        $this->expectException(\InvalidArgumentException::class);
        $money->compare($money1);
    }
}
