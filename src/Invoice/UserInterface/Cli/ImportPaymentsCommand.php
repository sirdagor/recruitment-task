<?php

declare(strict_types=1);

namespace App\Invoice\UserInterface\Cli;

use App\Invoice\Application\Command\AddInvoice;
use App\Invoice\Application\Command\AddPayment;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(
    name: 'import:payments',
    description: 'Import Payments.',
    hidden: false,
)]
class ImportPaymentsCommand extends Command
{
    public function __construct(
        private readonly ContainerBagInterface $containerBag,
        private readonly MessageBusInterface   $messageBus
    ) {
        parent::__construct('import:payments');
    }

    protected function configure(): void
    {
        $this->setDescription('Import payments')
            ->setHelp('Help message for the command');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $lineNumber = 0;
        $csvPath = $this->containerBag->get('kernel.project_dir') .
            DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'pay.csv';
        $file = fopen($csvPath, 'r');
        if ($file) {
            $output->writeln("Reading CSV file: $csvPath");
            while (($data = fgetcsv($file)) !== false) {
                $output->writeln("Importing row: $lineNumber");
                if ($lineNumber === 0) {
                    $lineNumber++;
                    continue;
                }
                try {
                    $this->messageBus->dispatch(new AddPayment($data));
                    $lineNumber++;
                } catch (\Throwable $exception) {
                    $errorMessage = $exception->getMessage();
                    $output->writeln("Error during importing row:$lineNumber error:$errorMessage");
                }
                $lineNumber++;
            }
            fclose($file);
        }

        return Command::SUCCESS;
    }
}
