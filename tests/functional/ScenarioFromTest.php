<?php

declare(strict_types=1);

namespace Tests\functional;

use Lendinvest\Common\Currency;
use Lendinvest\Common\Money;
use Lendinvest\Loan\Application\Command\CalculateInterest;
use Lendinvest\Loan\Application\Command\CloseLoan;
use Lendinvest\Loan\Application\Command\CreateLoan;
use Lendinvest\Loan\Application\Command\AddTranche;
use Lendinvest\Loan\Application\Command\Handler\AddTrancheHandler;
use Lendinvest\Loan\Application\Command\CreateInvestor;
use Lendinvest\Loan\Application\Command\Handler\CalculateInterestHandler;
use Lendinvest\Loan\Application\Command\Handler\CloseLoanHandler;
use Lendinvest\Loan\Application\Command\Handler\CreateInvestorHandler;
use Lendinvest\Loan\Application\Command\Handler\CreateLoanHandler;
use Lendinvest\Loan\Application\Command\Handler\InvestMoneyHandler;
use Lendinvest\Loan\Application\Command\OpenLoan;
use Lendinvest\Loan\Application\Command\Handler\OpenLoanHandler;
use Lendinvest\Loan\Application\Command\InvestMoney;
use Lendinvest\Loan\Domain\Exception\InvestorCannotInvest;
use Lendinvest\Loan\Domain\InterestCalculator;
use Lendinvest\Loan\Domain\Investor\InvestorId;
use Lendinvest\Loan\Domain\Investor\Investors;
use Lendinvest\Loan\Domain\Loans;
use Lendinvest\Loan\Infrastructure\InMemory\InvestorRepository;
use Lendinvest\Loan\Infrastructure\InMemory\LoanRepository;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class ScenarioFromTest extends TestCase
{
    /**
     * @var Loans
     */
    private $loans;

    /**
     * @var Investors
     */
    private $investors;

    /**
     * @var CreateLoanHandler
     */
    private $loanHandler;

    /**
     * @var AddTrancheHandler
     */
    private $trancheHandler;

    /**
     * @var CreateInvestorHandler
     */
    private $investorHandler;

    /**
     * @var OpenLoanHandler
     */
    private $openLoanHandler;

    /**
     * @var InvestMoneyHandler
     */
    private $investMoneyHandler;

    /**
     * @var CloseLoanHandler
     */
    private $closeLoanHandler;

    /**
     * @var CalculateInterestHandler
     */
    private $calculateInterest;

    public function setUp(): void
    {
        $this->loans = new LoanRepository();
        $this->investors = new InvestorRepository();
        $this->loanHandler = new CreateLoanHandler($this->loans);
        $this->trancheHandler = new AddTrancheHandler($this->loans);
        $this->investorHandler = new CreateInvestorHandler($this->investors);
        $this->openLoanHandler = new OpenLoanHandler($this->loans);
        $this->closeLoanHandler = new CloseLoanHandler($this->loans);
        $this->investMoneyHandler = new InvestMoneyHandler($this->loans, $this->investors);
        $calculator = new InterestCalculator();
        $this->calculateInterest = new CalculateInterestHandler($this->loans, $this->investors, $calculator);
    }

    /**
     * @test
     */
    public function scenario_from_test()
    {
        //Given a loan (start 01/10/2015 end 15/11/2015).
        $loanCommand = new CreateLoan('1', '2012-10-01', '2012-11-15');
        $this->loanHandler->__invoke($loanCommand);

        //Given the loan has 2 tranches called A and B (3% and 6% monthly interest rate) each with
        //1,000 pounds amount available.
        $trancheCommand = new AddTranche('1', '1', '3', '1000', 'GBP');
        $this->trancheHandler->__invoke($trancheCommand);
        $trancheCommand = new AddTranche('2', '1', '6', '1000', 'GBP');
        $this->trancheHandler->__invoke($trancheCommand);
        $openLoanCommand = new OpenLoan('1');
        $this->openLoanHandler->__invoke($openLoanCommand);

        //Given each investor has 1,000 pounds in his virtual wallet.
        $investorCommand = new CreateInvestor('1', '1000', 'GBP');
        $this->investorHandler->__invoke($investorCommand);
        $investorCommand = new CreateInvestor('2', '1000', 'GBP');
        $this->investorHandler->__invoke($investorCommand);
        $investorCommand = new CreateInvestor('3', '1000', 'GBP');
        $this->investorHandler->__invoke($investorCommand);
        $investorCommand = new CreateInvestor('4', '1000', 'GBP');
        $this->investorHandler->__invoke($investorCommand);

        //As “Investor 1” I’d like to invest 1,000 pounds on the tranche “A” on 03/10/2015: “ok”.
        $investCommand = new InvestMoney('1', '1000', 'GBP', '2012-10-03', '1', '1', '1');
        $this->investMoneyHandler->__invoke($investCommand);

        //As “Investor 2” I’d like to invest 1 pound on the tranche “A” on 04/10/2015: “exception”.
        try {
            $investCommand = new InvestMoney('2', '1', 'GBP', '2012-10-03', '2', '1', '1');
            $this->investMoneyHandler->__invoke($investCommand);
        } catch (\Exception $exception) {
            Assert::assertInstanceOf(InvestorCannotInvest::class, $exception);
        }

        //As “Investor 3” I’d like to invest 500 pounds on the tranche “B” on 10/10/2015: “ok”.
        $investCommand = new InvestMoney('3', '500', 'GBP', '2012-10-10', '3', '1', '2');
        $this->investMoneyHandler->__invoke($investCommand);

        //Closes loan because expired
        $closeLoanCommand = new CloseLoan('1');
        $this->closeLoanHandler->__invoke($closeLoanCommand);

        //As “Investor 4” I’d like to invest 1,100 pounds on the tranche “B” 25/10/2015: “exception”.
        try {
            $investCommand = new InvestMoney('4', '1100', 'GBP', '2012-11-25', '4', '1', '2');
            $this->investMoneyHandler->__invoke($investCommand);
        } catch (\Exception $exception) {
            Assert::assertInstanceOf(InvestorCannotInvest::class, $exception);
        }

        //On 01/11/2015 the system runs the interest calculation for the period 01/10/2015 -> 31/10/2015:
        $calculateCommand = new CalculateInterest('2012-10-01', '2012-10-31');
        $this->calculateInterest->__invoke($calculateCommand);

        //“Investor 1” earns 28.06 pounds
        $investor = $this->investors->get(InvestorId::fromString('1'));
        Assert::assertTrue($investor->balance()->equals(new Money('1028.06', new Currency('GBP'))));
        //“Investor 3” earns 21.29 pounds
        $investor = $this->investors->get(InvestorId::fromString('3'));
        Assert::assertTrue($investor->balance()->equals(new Money('1021.29', new Currency('GBP'))));
    }
}
