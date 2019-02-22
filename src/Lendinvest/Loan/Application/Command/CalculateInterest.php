<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Application\Command;

use DateTimeImmutable;

class CalculateInterest
{
    /**
     * @var string
     */
    private $start;

    /**
     * @var string
     */
    private $end;

    /**
     * CalculateInterest constructor.
     * @param string $start
     * @param string $end
     */
    public function __construct(string $start, string $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return DateTimeImmutable
     * @throws \Exception
     */
    public function start(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->start);
    }

    /**
     * @return DateTimeImmutable
     * @throws \Exception
     */
    public function end(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->end);
    }
}
