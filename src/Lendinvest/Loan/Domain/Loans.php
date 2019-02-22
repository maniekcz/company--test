<?php
declare(strict_types=1);

namespace Lendinvest\Loan\Domain;

interface Loans
{
    public function get(LoanId $id): Loan;
    public function save(Loan $loan);
    public function getByPeriod(\DateTimeImmutable $start, \DateTimeImmutable $end);
}
