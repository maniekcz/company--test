<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Domain;

use DateTimeImmutable;
use Lendinvest\Loan\Domain\Exception\CannotOpenLoan;
use Lendinvest\Loan\Domain\Exception\DateIsWrong;
use Lendinvest\Loan\Domain\Exception\InvestorCannotInvest;
use Lendinvest\Loan\Domain\Exception\TrancheAlreadyExists;
use Lendinvest\Loan\Domain\Exception\TrancheIsNotDefined;
use Lendinvest\Loan\Domain\Investment\Investment;
use Lendinvest\Loan\Domain\Tranche\Tranche;
use Lendinvest\Loan\Domain\Tranche\TrancheId;

final class Loan
{
    /**
     * @var LoanId
     */
    private $id;

    /**
     * @var DateTimeImmutable
     */
    private $startDate;

    /**
     * @var DateTimeImmutable
     */
    private $endDate;

    /**
     * @var Tranche[]
     */
    private $tranches;

    /**
     * @var StateLoan
     */
    private $state;

    /**
     * Loan constructor.
     * @param LoanId $id
     * @param DateTimeImmutable $startDate
     * @param DateTimeImmutable $endDate
     * @throws DateIsWrong
     */
    public function __construct(
        LoanId $id,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate
    ) {
        if ($startDate > $endDate) {
            throw new DateIsWrong('End date cannot be lower then start date');
        }
        $this->id = $id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->tranches = [];
        $this->state = StateLoan::NEW();
    }

    /**
     * @return LoanId
     */
    public function id(): LoanId
    {
        return $this->id;
    }

    /**
     * @return DateTimeImmutable
     */
    public function startDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    /**
     * @return DateTimeImmutable
     */
    public function endDate(): DateTimeImmutable
    {
        return $this->endDate;
    }

    /**
     * @return Tranche[]
     */
    public function tranches(): array
    {
        return $this->tranches;
    }

    /**
     * @return StateLoan
     */
    public function state(): StateLoan
    {
        return $this->state;
    }

    /**
     * @param LoanId $id
     * @param DateTimeImmutable $startDate
     * @param DateTimeImmutable $endDate
     * @return Loan
     * @throws DateIsWrong
     */
    public static function create(
        LoanId $id,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate
    ) {
        return new self($id, $startDate, $endDate);
    }

    /**
     * @throws CannotOpenLoan
     * @throws TrancheIsNotDefined
     */
    public function open()
    {
        if (!$this->state->equals(StateLoan::NEW())) {
            throw new CannotOpenLoan(sprintf('Loan cannot be opens, because is %s', $this->state));
        }

        if (0 >= count($this->tranches)) {
            throw new TrancheIsNotDefined();
        }
        $this->state = StateLoan::OPEN();
    }

    /**
     * @param Tranche $tranche
     * @throws \Exception
     */
    public function addTranche(Tranche $tranche)
    {
        if ($this->trancheExists($tranche->id())) {
            throw new TrancheAlreadyExists();
        }
        $this->tranches[$tranche->id()->toString()] = $tranche;
    }

    /**
     * @param TrancheId $id
     * @return bool
     */
    public function trancheExists(TrancheId $id): bool
    {
        return isset($this->tranches[$id->toString()]);
    }

    /**
     * @return bool
     */
    public function isOpen(): bool
    {
        return $this->state()->equals(StateLoan::OPEN());
    }

    /**
     * @param TrancheId $trancheId
     * @param Investment $investment
     * @throws \Exception
     */
    public function invest(
        TrancheId $trancheId,
        Investment $investment
    ) {
        if (!$this->isOpen()) {
            throw new InvestorCannotInvest(sprintf('Investor cannot invest, because loan is %s', $this->state()->toString()));
        }
        $this->tranches[$trancheId->toString()]->invest($investment);
    }
}
