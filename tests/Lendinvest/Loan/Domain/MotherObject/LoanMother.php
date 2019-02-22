<?php

declare(strict_types=1);

namespace Tests\Lendinvest\Loan\Domain\MotherObject;

use DateTimeImmutable;
use Lendinvest\Loan\Domain\Loan;
use Lendinvest\Loan\Domain\LoanId;

class LoanMother
{
    /**
     * @param string $id
     * @return Loan
     * @throws \Lendinvest\Loan\Domain\Exception\DateIsWrong
     */
    public static function withId(string $id) : Loan
    {
        return Loan::create(
            LoanId::fromString($id),
            new DateTimeImmutable(),
            new DateTimeImmutable()
        );
    }

    /**
     * @param string $id
     * @param string $startData
     * @param string $endData
     * @return Loan
     * @throws \Lendinvest\Loan\Domain\Exception\DateIsWrong
     */
    public static function withData(string $id, string $startData, string $endData) : Loan
    {
        return Loan::create(
            LoanId::fromString($id),
            new DateTimeImmutable($startData),
            new DateTimeImmutable($endData)
        );
    }
}
