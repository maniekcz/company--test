<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Application\Command\Handler;

use Lendinvest\Loan\Application\Command\OpenLoan;
use Lendinvest\Loan\Domain\Exception\CannotOpenLoan;
use Lendinvest\Loan\Domain\Exception\TrancheIsNotDefined;
use Lendinvest\Loan\Domain\Loans;

class OpenLoanHandler
{
    /**
     * @var Loans
     */
    private $loans;

    /**
     * CreateLoanHandler constructor.
     * @param Loans $loans
     */
    public function __construct(Loans $loans)
    {
        $this->loans = $loans;
    }

    /**
     * @param OpenLoan $command
     * @throws CannotOpenLoan
     * @throws TrancheIsNotDefined
     */
    public function __invoke(OpenLoan $command)
    {
        $loan = $this->loans->get($command->id());
        $loan->open();
        $this->loans->save($loan);
    }
}
