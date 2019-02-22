<?php

declare(strict_types=1);

namespace Tests\Lendinvest\Loan\Domain\MotherObject;

use Lendinvest\Loan\Domain\Tranche\Tranche;
use Lendinvest\Loan\Domain\Tranche\TrancheId;
use Tests\Lendinvest\Common\MotherObject\MoneyMother;

class TrancheMother
{
    /**
     * @param string $id
     * @return Tranche
     */
    public static function withId(string $id) : Tranche
    {
        return Tranche::create(
            TrancheId::fromString($id),
            3,
            MoneyMother::correct()
        );
    }

    /**
     * @param string $id
     * @param int $interest
     * @param string $amount
     * @param string $currency
     * @return Tranche
     */
    public static function withData(string $id, int $interest, string $amount, string $currency) : Tranche
    {
        return Tranche::create(
            TrancheId::fromString($id),
            $interest,
            MoneyMother::withData($amount, $currency)
        );
    }
}
