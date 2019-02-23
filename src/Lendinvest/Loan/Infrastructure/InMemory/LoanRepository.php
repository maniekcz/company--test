<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Infrastructure\InMemory;

use Lendinvest\Loan\Domain\Loan;
use Lendinvest\Loan\Domain\LoanId;
use Lendinvest\Loan\Domain\Loans;
use Exception;

class LoanRepository implements Loans
{
    private $loans = [];

    /**
     * @param LoanId $id
     * @return Loan
     * @throws Exception
     */
    public function get(LoanId $id): Loan
    {
        if (!isset($this->loans[$id->toString()])) {
            throw new Exception('Loan doesn\'t exist');
        }
        return $this->loans[$id->toString()];
    }

    /**
     * @param Loan $loan
     */
    public function save(Loan $loan)
    {
        $this->loans[$loan->id()->toString()] = $loan;
    }

    /**
     * @param \DateTimeImmutable $start
     * @param \DateTimeImmutable $end
     * @return Loan[]
     */
    public function getByPeriod(\DateTimeImmutable $start, \DateTimeImmutable $end): array
    {
        return array_filter($this->loans, function (Loan $loan) use ($start, $end) {
            return $loan->startDate() >= $start && $loan->endDate() >= $end;
        });
    }
}
