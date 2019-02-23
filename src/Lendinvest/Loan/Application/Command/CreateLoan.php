<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Application\Command;

use DateTimeImmutable;
use Lendinvest\Loan\Domain\LoanId;

class CreateLoan
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $startDate;

    /**
     * @var string
     */
    private $endDate;

    /**
     * CreateLoan constructor.
     * @param string $id
     * @param string $startDate
     * @param string $endDate
     */
    public function __construct(string $id, string $startDate, string $endDate)
    {
        $this->id = $id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return LoanId
     */
    public function id(): LoanId
    {
        return LoanId::fromString($this->id);
    }

    /**
     * @return DateTimeImmutable
     * @throws \Exception
     */
    public function startDate(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->startDate);
    }

    /**
     * @return DateTimeImmutable
     * @throws \Exception
     */
    public function endDate(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->endDate);
    }
}
