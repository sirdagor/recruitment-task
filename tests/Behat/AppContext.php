<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Invoice\Application\Command\AddCustomer;
use App\Invoice\Application\Command\AddInvoice;
use App\Invoice\Application\Command\AddPayment;
use Behat\Behat\Context\Context;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use PHPUnit\Framework\Assert;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\String\Exception\RuntimeException;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

final class AppContext implements Context
{

    public function __construct(
        private readonly KernelBrowser          $client,
        private readonly MessageBusInterface    $messageBus,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * @BeforeScenario
     */
    public function purgeDatabase()
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();
    }

    /**
     * @Given There are invoices:
     */
    public function thereAreInvoices(TableNode $table)
    {
        foreach ($table as $row) {
            $row = array_values($row);
            $this->messageBus->dispatch(new AddInvoice($row));
            $this->messageBus->dispatch(new AddCustomer($row[2]));
        }
    }


    /**
     * @Given There are payments:
     */
    public function thereArePayments(TableNode $table)
    {
        foreach ($table as $row) {
            $row = array_values($row);
            $this->messageBus->dispatch(new AddPayment($row));
        }
    }


    /**
     * @Then the response for user should look like this :path
     */
    public function theResponseForUserShouldLookLikeThis(string $path): void
    {
        $actualContent = $this->client->getResponse()->getContent();
        if (false === $actualContent) {
            throw new RuntimeException('Couldn\'t fetch response content');
        }

        $snapshotPath = sprintf('%s/features/snapshots/%s.json', dirname(__FILE__, 3), $path);
        if (!file_exists($snapshotPath)) {
            throw new RuntimeException("File: $snapshotPath don't exists");
        }

        Assert::assertJsonStringEqualsJsonFile($snapshotPath, $actualContent);
    }

    /**
     * @When I request for the balance
     */
    public function iRequestForTheBalance()
    {
        $this->client->request('GET', "/api/customers/balances", [], []);
    }

    /**
     * @When I request for the balance for the customerId :customerId
     */
    public function iRequestForTheBalanceForTheCustomerid(string $customerId)
    {
        $this->client->request('GET', "/api/customers/balances?customerId=$customerId", [], []);
    }


    /**
     * @Then the system should respond with status code :code
     */
    public function theSystemShouldRespondWithStatusCode(int $code): void
    {
        Assert::assertSame($code, $this->client->getResponse()->getStatusCode());
    }

}
