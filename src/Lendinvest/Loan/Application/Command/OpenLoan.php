<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Application\Command;

use Lendinvest\Loan\Domain\LoanId;

class OpenLoan
{
    /**
     * @var string
     */
    private $id;

    /**
     * OpenLoan constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return LoanId
     */
    public function id(): LoanId
    {
        return LoanId::fromString($this->id);
    }
}
