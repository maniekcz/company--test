<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Domain\Tranche;

use Lendinvest\Common\Money;

class Tranche
{
    /**
     * @var TrancheId
     */
    private $id;

    /**
     * @var int
     */
    private $interest;

    /**
     * @var Money
     */
    private $amount;


    /**
     * @return TrancheId
     */
    public function id(): TrancheId
    {
        return $this->id;
    }

    /**
     * Tranche constructor.
     * @param TrancheId $id
     * @param int $interest
     * @param Money $amount
     */
    public function __construct(TrancheId $id, int $interest, Money $amount)
    {
        $this->id = $id;
        $this->interest = $interest;
        $this->amount = $amount;
    }

    /**
     * @param TrancheId $id
     * @param int $interest
     * @param Money $amount
     * @return Tranche
     */
    public static function create(TrancheId $id, int $interest, Money $amount): Tranche
    {
        return new self($id, $interest, $amount);
    }
}
