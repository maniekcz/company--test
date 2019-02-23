<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Application\Command\Handler;

use Lendinvest\Loan\Application\Command\AddTranche;
use Lendinvest\Loan\Domain\Loans;
use Lendinvest\Loan\Domain\Tranche\Tranche;

class AddTrancheHandler
{
    /**
     * @var Loans
     */
    private $loans;

    /**
     * AddTrancheHandler constructor.
     * @param Loans $loans
     */
    public function __construct(Loans $loans)
    {
        $this->loans = $loans;
    }

    /**
     * @param AddTranche $command
     * @throws \Exception
     */
    public function __invoke(AddTranche $command)
    {
        $loan = $this->loans->get($command->loanId());
        $tranche = Tranche::create(
            $command->id(),
            $command->interest(),
            $command->amount()
        );
        $loan->addTranche($tranche);
        $this->loans->save($loan);
    }
}
