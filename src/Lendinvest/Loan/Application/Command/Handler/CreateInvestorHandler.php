<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Application\Command\Handler;

use Lendinvest\Loan\Application\Command\CreateInvestor;
use Lendinvest\Loan\Domain\Investor\Investor;
use Lendinvest\Loan\Domain\Investor\Investors;

class CreateInvestorHandler
{
    /**
     * @var Investors
     */
    private $investors;

    /**
     * CreateInvestorHandler constructor.
     * @param Investors $investors
     */
    public function __construct(Investors $investors)
    {
        $this->investors = $investors;
    }

    /**
     * @param CreateInvestor $command
     */
    public function __invoke(CreateInvestor $command)
    {
        $investor = Investor::create(
            $command->id(),
            $command->amount()
        );

        $this->investors->save($investor);
    }
}
