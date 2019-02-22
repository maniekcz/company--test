<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Domain\Investor;

use Lendinvest\Common\Money;
use Lendinvest\Loan\Domain\Exception\InvestorCannotInvest;

class Investor
{

    /**
     * @var InvestorId
     */
    private $id;

    /**
     * @var Money
     */
    private $balance;

    /**
     * Investor constructor.
     * @param InvestorId $id
     * @param Money $balance
     */
    public function __construct(InvestorId $id, Money $balance)
    {
        $this->id = $id;
        $this->balance = $balance;
    }

    /**
     * @return InvestorId
     */
    public function id(): InvestorId
    {
        return $this->id;
    }

    /**
     * @return Money
     */
    public function balance(): Money
    {
        return $this->balance;
    }

    /**
     * @param InvestorId $id
     * @param Money $balance
     * @return Investor
     */
    public static function create(InvestorId $id, Money $balance): Investor
    {
        return new self($id, $balance);
    }

    /**
     * @param Money $amount
     * @throws InvestorCannotInvest
     */
    public function invest(Money $amount)
    {
        if (!$this->canInvest($amount)) {
            throw new InvestorCannotInvest(sprintf('Investor has not enough money, needs at least %s %s', $amount->getAmount(), $amount->currency()->getCode()));
        }
        $this->balance = $this->balance->subtract($amount);
    }

    /**
     * @param Money $amount
     */
    public function increaseBalance(Money $amount)
    {
        $this->balance = $this->balance->add($amount);
    }

    /**
     * @param Money $money
     * @return bool
     */
    public function canInvest(Money $money): bool
    {
        return $this->balance->greaterThanOrEqual($money);
    }
}
