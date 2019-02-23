<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Domain\Tranche;

use Lendinvest\Common\Money;
use Lendinvest\Loan\Domain\Exception\InvestorCannotInvest;
use Lendinvest\Loan\Domain\Investment\Investment;

class Tranche
{
    /**
     * @var TrancheId
     */
    private $id;

    /**
     * @var string
     */
    private $interest;

    /**
     * @var Money
     */
    private $amount;

    /**
     * Tranche constructor.
     * @param TrancheId $id
     * @param string $interest
     * @param Money $amount
     */
    public function __construct(TrancheId $id, string $interest, Money $amount)
    {
        $this->id = $id;
        $this->interest = $interest;
        $this->amount = $amount;
        $this->investments = [];
    }

    /**
     * @return TrancheId
     */
    public function id(): TrancheId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function interest(): string
    {
        return $this->interest;
    }

    /**
     * @return Money
     */
    public function amount(): Money
    {
        return $this->amount;
    }

    /**
     * @param TrancheId $id
     * @param string $interest
     * @param Money $amount
     * @return Tranche
     */
    public static function create(TrancheId $id, string $interest, Money $amount): Tranche
    {
        return new self($id, $interest, $amount);
    }

    /**
     * @param Investment $investment
     * @throws InvestorCannotInvest
     */
    public function invest(Investment $investment)
    {
        if (!$this->isInvestable($investment->amount())) {
            throw new InvestorCannotInvest('All amount has been used for this tranche.');
        }
        $this->amount = $this->amount->subtract($investment->amount());
    }

    /**
     * @param Money $money
     * @return bool
     */
    public function isInvestable(Money $money): bool
    {
        return $this->amount->greaterThanOrEqual($money);
    }
}
