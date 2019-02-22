<?php
declare(strict_types=1);

namespace Lendinvest\Loan\Domain\Investor;

interface Investors
{
    public function get(InvestorId $id): Investor;
    public function save(Investor $investor);
}