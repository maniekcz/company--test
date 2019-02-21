<?php

declare(strict_types=1);

namespace Tests\Lendinvest\Common;

use InvalidArgumentException;
use Lendinvest\Common\Currency;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{

    /**
     * @test
     */
    public function when_data_is_correct_then_currency_can_be_create()
    {
        $currency = new Currency('GBP');
        Assert::assertEquals('GBP', $currency->getCode());
        Assert::assertEquals('GBP', $currency);
    }

    /**
     * @test
     */
    public function when_currency_code_is_empty_then_currency_cannot_be_create()
    {
        $this->expectException(InvalidArgumentException::class);
        new Currency('');
    }

    /**
     * @test
     */
    public function when_create_two_the_same_currency_then_they_are_equal()
    {
        $currency = new Currency('GBP');
        $currency1 = new Currency('GBP');
        Assert::assertTrue($currency->equals($currency1));
    }

}