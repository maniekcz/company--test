<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Domain\Tranche;

use Lendinvest\Common\Money;
use Lendinvest\Loan\Domain\Exception\InvestmentAlreadyExists;
use Lendinvest\Loan\Domain\Exception\InvestorCannotInvest;
use Lendinvest\Loan\Domain\Investment\Investment;
use Lendinvest\Loan\Domain\Investment\InvestmentId;

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
     * @var Investment[]
     */
    private $investments;

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
     * @return int
     */
    public function interest(): int
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
     * @return array
     */
    public function investments(): array
    {
        return $this->investments;
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

    /**
     * @param Investment $investment
     * @throws InvestorCannotInvest
     * @throws InvestmentAlreadyExists
     */
    public function invest(Investment $investment)
    {
        if (!$this->isInvestable($investment->amount())) {
            throw new InvestorCannotInvest('All amount has been used for this tranche.');
        }
        if ($this->investmentExists($investment->id())) {
            throw new InvestmentAlreadyExists();
        }
        $this->investments[$investment->id()->toString()] = $investment;
        $this->amount = $this->amount->subtract($investment->amount());
    }

    /**
     * @param InvestmentId $id
     * @return bool
     */
    public function investmentExists(InvestmentId $id): bool
    {
        return isset($this->investments[$id->toString()]);
    }

    /**
     * @param Money $money
     * @return bool
     */
    public function isInvestable(Money $money): bool
    {
        return $this->amount->getAmount() >= $money->getAmount();
    }
}
