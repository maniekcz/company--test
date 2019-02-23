<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Application\Command\Handler;

use Lendinvest\Loan\Application\Command\CloseLoan;
use Lendinvest\Loan\Domain\Exception\CannotClosedLoan;
use Lendinvest\Loan\Domain\Loans;

class CloseLoanHandler
{
    /**
     * @var Loans
     */
    private $loans;

    /**
     * CloseLoanHandler constructor.
     * @param Loans $loans
     */
    public function __construct(Loans $loans)
    {
        $this->loans = $loans;
    }

    /**
     * @param CloseLoan $command
     * @throws CannotClosedLoan
     */
    public function __invoke(CloseLoan $command)
    {
        $loan = $this->loans->get($command->id());
        $loan->close();
        $this->loans->save($loan);
    }
}
