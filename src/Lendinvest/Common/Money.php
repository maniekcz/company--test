<?php

declare(strict_types=1);

namespace Lendinvest\Common;

class Money
{
    /**
     * @var string
     */
    private $amount;

    /**
     * @var Currency
     */
    private $currency;

    /**
     * @var int
     */
    private $scale;

    /**
     * Money constructor.
     * @param string $amount
     * @param Currency $currency
     * @param int $scale
     */
    public function __construct(string $amount, Currency $currency, int $scale = 2)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->scale = $scale;
    }
}
