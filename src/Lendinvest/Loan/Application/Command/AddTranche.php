<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Application\Command;

use Lendinvest\Common\Currency;
use Lendinvest\Common\Money;
use Lendinvest\Loan\Domain\LoanId;
use Lendinvest\Loan\Domain\Tranche\TrancheId;

class AddTranche
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $loanId;

    /**
     * @var string
     */
    private $interest;

    /**
     * @var string
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * AddTranche constructor.
     * @param string $id
     * @param string $loanId
     * @param string $interest
     * @param string $amount
     * @param string $currency
     */
    public function __construct(string $id, string $loanId, string $interest, string $amount, string $currency)
    {
        $this->id = $id;
        $this->loanId = $loanId;
        $this->interest = $interest;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @return TrancheId
     */
    public function id(): TrancheId
    {
        return TrancheId::fromString($this->id);
    }

    /**
     * @return LoanId
     */
    public function loanId(): LoanId
    {
        return LoanId::fromString($this->loanId);
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
        return new Money($this->amount, new Currency($this->currency));
    }
}
