<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Domain\Investor;

use Lendinvest\Common\Money;

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
     * @param InvestorId $id
     * @param Money $balance
     * @return Investor
     */
    public static function create(InvestorId $id, Money $balance): Investor
    {
        return new self($id, $balance);
    }

    /**
     * @param Money $money
     * @return bool
     */
    public function canInvest(Money $money): bool
    {
        return $this->balance->getAmount() > $money->getAmount();
    }

}