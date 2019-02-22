<?php

declare(strict_types=1);

namespace Tests\Lendinvest\Common\MotherObject;

use Lendinvest\Common\Currency;
use Lendinvest\Common\Money;

class MoneyMother
{
    /**
     * @return Money
     */
    public static function correct() : Money
    {
        return new Money('1000', CurrencyMother::correct());
    }

    /**
     * @param string $amount
     * @return Money
     */
    public static function withAmount(string $amount) : Money
    {
        return new Money($amount, CurrencyMother::correct());
    }

    /**
     * @param string $amount
     * @param string $currency
     * @return Money
     */
    public static function withData(string $amount, string $currency) : Money
    {
        return new Money($amount, CurrencyMother::withCurrency($currency));
    }
}
