<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Application\Command;

use Lendinvest\Common\Currency;
use Lendinvest\Common\Money;
use Lendinvest\Loan\Domain\Investor\InvestorId;

class CreateInvestor
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $amount;

    /**
     * @var string
     */
    private $currency;

    /**
     * CreateInvestor constructor.
     * @param string $id
     * @param string $amount
     * @param string $currency
     */
    public function __construct(string $id, string $amount, string $currency)
    {
        $this->id = $id;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @return InvestorId
     */
    public function id(): InvestorId
    {
        return InvestorId::fromString($this->id);
    }

    /**
     * @return Money
     */
    public function amount(): Money
    {
        return new Money($this->amount, new Currency($this->currency));
    }
}
