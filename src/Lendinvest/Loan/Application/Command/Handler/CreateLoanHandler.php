<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Application\Command\Handler;

use Lendinvest\Loan\Application\Command\CreateLoan;
use Lendinvest\Loan\Domain\Exception\DateIsWrong;
use Lendinvest\Loan\Domain\Loan;
use Lendinvest\Loan\Domain\Loans;

class CreateLoanHandler
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
     * @param CreateLoan $command
     * @throws DateIsWrong
     */
    public function __invoke(CreateLoan $command)
    {
        $loan = Loan::create(
            $command->id(),
            $command->startDate(),
            $command->endDate()
        );
        $this->loans->save($loan);
    }
}
