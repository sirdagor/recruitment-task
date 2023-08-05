<?php

declare(strict_types=1);

namespace App\Invoice\Application\CommandHandler;

use App\Invoice\Application\Command\AddInvoice;
use App\Invoice\Domain\Entity\CustomerId;
use App\Invoice\Domain\Entity\Invoice;
use App\Invoice\Domain\Exception\ValidationException;
use App\Invoice\Domain\Repository\InvoiceRepository;
use App\Invoice\Domain\Type;
use Money\Currency;
use Money\Money;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Ramsey\Uuid\Uuid;

#[AsMessageHandler]
class AddInvoiceHandler
{
    public function __construct(
        private readonly InvoiceRepository $invoiceRepository,
        private readonly LoggerInterface   $logger
    ) {
    }

    public function __invoke(AddInvoice $command): void
    {
        $invoiceArray = $command->invoiceArray();
        try {
            $currency = new Currency($invoiceArray[7]);
            $invoice = new Invoice(
                Uuid::fromString($invoiceArray[0]),
                Type::tryFrom($invoiceArray[1]),
                new CustomerId(Uuid::fromString($invoiceArray[2])),
                $invoiceArray[3],
                new Money(intval(floatval($invoiceArray[4]) * 100), $currency),
                new Money(intval(floatval($invoiceArray[5]) * 100), $currency),
                new Money(intval(floatval($invoiceArray[6]) * 100), $currency),
                $currency,
                new \DateTimeImmutable($invoiceArray[8])
            );
            $this->invoiceRepository->save($invoice);
        } catch (ValidationException $exception) {
            $this->logger->info($exception->getMessage());
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage());
        }

    }

}
