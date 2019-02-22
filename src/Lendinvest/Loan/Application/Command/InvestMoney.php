<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Application\Command;

use DateTimeImmutable;
use Lendinvest\Common\Currency;
use Lendinvest\Common\Money;
use Lendinvest\Loan\Domain\Investment\InvestmentId;
use Lendinvest\Loan\Domain\Investor\InvestorId;
use Lendinvest\Loan\Domain\LoanId;
use Lendinvest\Loan\Domain\Tranche\TrancheId;

class InvestMoney
{
    /**
     * @var string
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $investorId;

    /**
     * @var string
     */
    private $loanId;

    /**
     * @var string
     */
    private $trancheId;

    /**
     * @var string
     */
    private $investmentId;

    /**
     * @var string
     */
    private $created;

    public function __construct(string $investmentId, string $amount, string $created, string $currency, string $investorId, string $loanId, string $trancheId)
    {
        $this->investmentId = $investmentId;
        $this->amount = $amount;
        $this->created = $created;
        $this->currency = $currency;
        $this->investorId = $investorId;
        $this->loanId = $loanId;
        $this->trancheId = $trancheId;
    }

    /**
     * @return InvestmentId
     */
    public function investmentId(): InvestmentId
    {
        return InvestmentId::fromString($this->investmentId);
    }

    /**
     * @return Money
     */
    public function amount(): Money
    {
        return new Money($this->amount, new Currency($this->currency));
    }

    /**
     * @return DateTimeImmutable
     * @throws \Exception
     */
    public function created(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->created);
    }

    /**
     * @return InvestorId
     */
    public function investorId(): InvestorId
    {
        return InvestorId::fromString($this->investorId);
    }

    /**
     * @return LoanId
     */
    public function loanId(): LoanId
    {
        return LoanId::fromString($this->loanId);
    }

    /**
     * @return TrancheId
     */
    public function trancheId(): TrancheId
    {
        return TrancheId::fromString($this->trancheId);
    }
}