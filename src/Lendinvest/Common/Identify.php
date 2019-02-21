<?php

declare(strict_types=1);

namespace Lendinvest\Common;

trait Identify
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

    /**
     * @param string $value
     * @return self
     */
    public static function fromString(string $value): self
    {
        return new static($value);
    }
}
