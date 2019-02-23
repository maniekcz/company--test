<?php

declare(strict_types=1);

namespace Lendinvest\Loan\Domain;

use DateTimeImmutable;
use Lendinvest\Loan\Domain\Exception\CannotClosedLoan;
use Lendinvest\Loan\Domain\Exception\CannotOpenLoan;
use Lendinvest\Loan\Domain\Exception\DateIsWrong;
use Lendinvest\Loan\Domain\Exception\InvestmentAlreadyExists;
use Lendinvest\Loan\Domain\Exception\InvestorCannotInvest;
use Lendinvest\Loan\Domain\Exception\TrancheAlreadyExists;
use Lendinvest\Loan\Domain\Exception\TrancheIsNotDefined;
use Lendinvest\Loan\Domain\Investment\Investment;
use Lendinvest\Loan\Domain\Investment\InvestmentId;
use Lendinvest\Loan\Domain\Investment\StateInvestment;
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
     * @var Investment[]
     */
    private $investments;

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
     * @return array
     */
    public function investments(): array
    {
        return $this->investments;
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
     * @throws CannotClosedLoan
     */
    public function close()
    {
        if (!$this->state->equals(StateLoan::OPEN())) {
            throw new CannotClosedLoan(sprintf('Loan cannot be closed, because is %s', $this->state));
        }

        $this->state = StateLoan::CLOSED();
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
     * @param TrancheId $trancheId
     * @return Tranche|mixed
     * @throws TrancheIsNotDefined
     */
    public function getTranche(TrancheId $trancheId)
    {
        if (!$this->trancheExists($trancheId)) {
            throw new TrancheIsNotDefined(sprintf('Tranche with ID %s is not defined', $trancheId->toString()));
        }
        return $this->tranches[$trancheId->toString()];
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
     * @return array
     */
    public function getNewInvestments(): array
    {
        return array_filter($this->investments, function (Investment $investment) {
            return $investment->state()->equals(StateInvestment::NEW());
        });
    }

    /**
     * @param Investment $investment
     * @throws \Exception
     */
    public function invest(Investment $investment)
    {
        if (!$this->isOpen()) {
            throw new InvestorCannotInvest(sprintf('Investor cannot invest, because loan is %s', $this->state()->toString()));
        }
        if ($this->investmentExists($investment->id())) {
            throw new InvestmentAlreadyExists();
        }
        $this->getTranche($investment->trancheId())->invest($investment);

        $this->investments[$investment->id()->toString()] = $investment;
    }

    /**
     * @param InvestmentId $id
     * @return bool
     */
    public function investmentExists(InvestmentId $id): bool
    {
        return isset($this->investments[$id->toString()]);
    }
}
