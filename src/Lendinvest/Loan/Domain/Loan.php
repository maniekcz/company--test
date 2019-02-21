<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Domain;

use DateTimeImmutable;

final class Loan
{
    /**
     * @var LoanId
     */
    private $id;

    /**
     * @var DateTimeImmutable
     */
    private $startDate;

    /**
     * @var DateTimeImmutable
     */
    private $endDate;

    /**
     * Loan constructor.
     * @param LoanId $id
     * @param DateTimeImmutable $startDate
     * @param DateTimeImmutable $endDate
     */
    public function __construct(
        LoanId $id,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate
    ) {
        $this->id = $id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return LoanId
     */
    public function id(): LoanId
    {
        return $this->id;
    }

    /**
     * @param LoanId $id
     * @param DateTimeImmutable $startDate
     * @param DateTimeImmutable $endDate
     * @return Loan
     */
    public static function create(
        LoanId $id,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate
    ) {
        return new self($id, $startDate, $endDate);
    }
}
