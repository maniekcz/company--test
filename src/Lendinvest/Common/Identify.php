<?php

declare(strict_types=1);

namespace Lendinvest\Common;

abstract class Identify
{
    /**
     * @var string
     */
    private $id;

    /**
     * Identify constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->id;
    }

    public function toString(): string
    {
        return $this->id;
    }

    /**
     * @param string $value
     * @return static
     */
    public static function fromString(string $value)
    {
        return new static($value);
    }
}
