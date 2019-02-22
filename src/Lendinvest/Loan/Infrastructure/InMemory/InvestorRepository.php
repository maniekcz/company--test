<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Infrastructure\InMemory;

use Exception;
use Lendinvest\Loan\Domain\Investor\Investor;
use Lendinvest\Loan\Domain\Investor\InvestorId;
use Lendinvest\Loan\Domain\Investor\Investors;

class InvestorRepository implements Investors
{
    private $investors = [];

    /**
     * @param InvestorId $id
     * @return Investor
     * @throws Exception
     */
    public function get(InvestorId $id): Investor
    {
        if (!isset($this->investors[$id->toString()])) {
            throw new Exception();
        }
        return $this->investors[$id->toString()];
    }

    /**
     * @param Investor $investor
     */
    public function save(Investor $investor)
    {
        $this->investors[$investor->id()->toString()] = $investor;
    }
}
