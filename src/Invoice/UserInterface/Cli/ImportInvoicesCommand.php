<?php

declare(strict_types=1);

namespace App\Invoice\UserInterface\Cli;

use App\Invoice\Application\Command\AddCustomer;
use App\Invoice\Application\Command\AddInvoice;
use App\Invoice\Domain\Exception\ValidationException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'import:invoices',
    description: 'Import Invoices.',
    hidden: false,
)]
class ImportInvoicesCommand extends Command
{
    public function __construct(
        private readonly ContainerBagInterface $containerBag,
        private readonly MessageBusInterface   $messageBus
    ) {
        parent::__construct('import:invoices');
    }

    protected function configure(): void
    {
        $this->setDescription('Import invoices')
            ->setHelp('Help message for the command');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $lineNumber = 0;
        $csvPath = $this->containerBag->get('kernel.project_dir') .
            DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'inv.csv';
        $file = fopen($csvPath, 'r');
        if ($file) {
            $output->writeln("Reading CSV file: $csvPath");
            while (($data = fgetcsv($file)) !== false) {
                $output->writeln("Importing row: $lineNumber");
                if($lineNumber === 0) {
                    $lineNumber++;
                    continue;
                }
                try {
                    $this->messageBus->dispatch(new AddInvoice($data));
                    $this->messageBus->dispatch(new AddCustomer($data[2]));
                    $lineNumber++;
                } catch (ValidationException $exception) {
                    $errorMessage = $exception->getMessage();
                    $output->writeln("Error during importing row:$lineNumber error:$errorMessage");
                } catch (\Throwable $exception) {
                    $errorMessage = $exception->getMessage();
                    $output->writeln("Error during importing row:$lineNumber error:$errorMessage");
                }
            }
            fclose($file);
        }


        return Command::SUCCESS;
    }
}
