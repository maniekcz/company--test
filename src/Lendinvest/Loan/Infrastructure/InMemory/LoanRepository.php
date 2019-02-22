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
        if(!isset($this->loans[$id->toString()])) {
            throw new Exception();
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

}