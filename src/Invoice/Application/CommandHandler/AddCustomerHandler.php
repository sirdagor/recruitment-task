<?php

declare(strict_types=1);

namespace App\Invoice\Application\CommandHandler;

use App\Invoice\Application\Command\AddCustomer;
use App\Invoice\Domain\Entity\Customer;
use App\Invoice\Domain\Entity\CustomerId;
use App\Invoice\Domain\Repository\CustomerRepository;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class AddCustomerHandler
{
    public function __construct(
        private readonly CustomerRepository $customerRepository,
        private readonly LoggerInterface    $logger
    ) {
    }

    public function __invoke(AddCustomer $command): void
    {
        $customerId = $command->customerId();
        try {
            $this->customerRepository->save(new Customer(new CustomerId(Uuid::fromString($customerId))));
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage());
        }

    }

}
