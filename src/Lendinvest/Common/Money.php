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

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * @return Currency
     */
    public function currency(): Currency
    {
        return $this->currency;
    }

    /**
     * @param Money ...$subtrahends
     * @return Money
     */
    public function subtract(Money ...$subtrahends): Money
    {
        $amount = $this->amount;
        foreach ($subtrahends as $subtrahend) {
            $this->assertSameCurrency($subtrahend);
            $amount = bcsub($amount, $subtrahend->amount, $this->scale);
        }
        return new self($amount, $this->currency);
    }

    /**
     * @param Money $other
     */
    private function assertSameCurrency(Money $other)
    {
        if (!$this->isSameCurrency($other)) {
            throw new \InvalidArgumentException('Currencies must be identical');
        }
    }

    /**
     * @param Money $other
     * @return bool
     */
    public function isSameCurrency(Money $other): bool
    {
        return $this->currency->equals($other->currency);
    }

    /**
     * @param Money $other
     * @return bool
     */
    public function equals(Money $other)
    {
        return $this->isSameCurrency($other) && $this->amount === $other->amount;
    }

    /**
     * @param Money $other
     * @return mixed
     */
    public function compare(Money $other)
    {
        $this->assertSameCurrency($other);
        return bccomp($this->amount, $other->amount, $this->scale);
    }

    /**
     * @param Money $other
     * @return bool
     */
    public function greaterThan(Money $other)
    {
        return $this->compare($other) > 0;
    }

    /**
     * @param Money $other
     * @return bool
     */
    public function greaterThanOrEqual(Money $other)
    {
        return $this->compare($other) >= 0;
    }

    /**
     * @param Money $other
     * @return bool
     */
    public function lessThan(Money $other)
    {
        return $this->compare($other) < 0;
    }

    /**
     * @param Money $other
     * @return bool
     */
    public function lessThanOrEqual(Money $other)
    {
        return $this->compare($other) <= 0;
    }
}