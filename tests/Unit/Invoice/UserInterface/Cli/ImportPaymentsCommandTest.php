<?php

declare(strict_types=1);

namespace App\Tests\Unit\Invoice\UserInterface\Cli;

use App\Invoice\Application\Command\AddPayment;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use App\Invoice\UserInterface\Cli\ImportPaymentsCommand;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ImportPaymentsCommandTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    public function testExecute()
    {
        $application = new Application(self::$kernel);
        $containerBag = $this->createMock(ContainerBagInterface::class);
        $messageBus = $this->createMock(MessageBusInterface::class);
        $application->add(new ImportPaymentsCommand($containerBag, $messageBus));

        $command = $application->find('import:payments');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);
        $messageBus->dispatch(
            new AddPayment()
        );

        // Assertions for the output or other expectations
        $this->assertStringContainsString('Expected Output Text', $commandTester->getDisplay());
        $this->assertSame(0, $commandTester->getStatusCode());
    }
}