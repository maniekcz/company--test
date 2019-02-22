<?php

declare(strict_types=1);

namespace Tests\Lendinvest\Common\MotherObject;

use Lendinvest\Common\Currency;

class CurrencyMother
{
    /**
     * @return Currency
     */
    public static function correct() : Currency
    {
        return new Currency('GBP');
    }

    /**
     * @param string $currency
     * @return Currency
     */
    public static function withCurrency(string $currency) : Currency
    {
        return new Currency($currency);
    }
}
