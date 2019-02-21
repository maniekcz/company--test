<?php

declare(strict_types=1);

namespace Lendinvest\Common;

final class Currency
{
    /**
     * @var string
     */
    private $code;

    /**
     * @param string $code
     */
    public function __construct(string $code)
    {
        if ($code === '') {
            throw new \InvalidArgumentException('Currency code should not be empty string');
        }

        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param Currency $other
     * @return bool
     */
    public function equals(Currency $other): bool
    {
        return $this->code === $other->code;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->code;
    }
}
