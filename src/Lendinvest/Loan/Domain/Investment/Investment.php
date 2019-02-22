<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Domain\Investment;

use DateTimeImmutable;
use Lendinvest\Common\Money;
use Lendinvest\Loan\Domain\Exception\InvestorCannotInvest;
use Lendinvest\Loan\Domain\Investor\Investor;
use Lendinvest\Loan\Domain\Investor\InvestorId;
use Lendinvest\Loan\Domain\Tranche\Tranche;
use Lendinvest\Loan\Domain\Tranche\TrancheId;

class Investment
{
    /**
     * @var InvestmentId
     */
    private $id;

    /**
     * @var InvestorId
     */
    private $investorId;

    /**
     * @var TrancheId
     */
    private $trancheId;

    /**
     * @var Money
     */
    private $amount;

    /**
     * @var DateTimeImmutable
     */
    private $created;

    /**
     * Investment constructor.
     * @param InvestmentId $id
     * @param InvestorId $investorId
     * @param TrancheId $trancheId
     * @param Money $amount
     * @param DateTimeImmutable $created
     */
    public function __construct(InvestmentId $id, InvestorId $investorId, TrancheId $trancheId, Money $amount, DateTimeImmutable $created)
    {
        $this->id = $id;
        $this->investorId = $investorId;
        $this->trancheId = $trancheId;
        $this->amount = $amount;
        $this->created = $created;
    }

    /**
     * @param InvestmentId $id
     * @param Investor $investor
     * @param Tranche $tranche
     * @param Money $amount
     * @param DateTimeImmutable $created
     * @return Investment
     * @throws InvestorCannotInvest
     */
    public static function create(InvestmentId $id, Investor $investor, Tranche $tranche, Money $amount, DateTimeImmutable $created)
    {
        if (!$investor->canInvest($amount)) {
            throw new InvestorCannotInvest(sprintf('Investor has not enough money, needs at least %s %s', $amount->getAmount(), $amount->currency()->getCode()));
        }

        return new self($id, $investor->id(), $tranche->id(), $amount, $created);
    }

    /**
     * @return InvestmentId
     */
    public function id(): InvestmentId
    {
        return $this->id;
    }

    /**
     * @return Money
     */
    public function amount(): Money
    {
        return $this->amount;
    }

    /**
     * @return InvestorId
     */
    public function investorId(): InvestorId
    {
        return $this->investorId;
    }

    /**
     * @return TrancheId
     */
    public function trancheId(): TrancheId
    {
        return $this->trancheId;
    }

    /**
     * @return DateTimeImmutable
     */
    public function created(): DateTimeImmutable
    {
        return $this->created;
    }
}
