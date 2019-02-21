<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Domain;

use DateTimeImmutable;
use Lendinvest\Loan\Domain\Exception\DateIsWrong;
use Lendinvest\Loan\Domain\Tranche\Tranche;
use Lendinvest\Loan\Domain\Tranche\TrancheId;

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
     * @var Tranche[]
     */
    private $tranches;

    /**
     * Loan constructor.
     * @param LoanId $id
     * @param DateTimeImmutable $startDate
     * @param DateTimeImmutable $endDate
     * @throws DateIsWrong
     */
    public function __construct(
        LoanId $id,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate
    ) {
        if ($startDate > $endDate) {
            throw new DateIsWrong('End date cannot be lower then start date');
        }
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
     * @throws DateIsWrong
     */
    public static function create(
        LoanId $id,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate
    ) {
        return new self($id, $startDate, $endDate);
    }

    /**
     * @param Tranche $tranche
     * @throws \Exception
     */
    public function addTranche(Tranche $tranche)
    {
        if ($this->trancheExists($tranche->id())) {
            throw new \Exception('Tranche already exists');
        }
        $this->tranches[$tranche->id()->toString()] = $tranche;
    }

    /**
     * @param TrancheId $id
     * @return bool
     */
    public function trancheExists(TrancheId $id): bool
    {
        return isset($this->tranches[$id->toString()]);
    }
}
