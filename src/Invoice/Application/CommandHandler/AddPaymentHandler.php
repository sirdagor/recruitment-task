<?php

declare(strict_types=1);

namespace App\Invoice\Application\CommandHandler;

use App\Invoice\Application\Command\AddPayment;
use App\Invoice\Domain\Entity\CustomerId;
use App\Invoice\Domain\Entity\Payment;
use App\Invoice\Domain\Repository\PaymentRepository;
use Money\Currency;
use Money\Money;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Ramsey\Uuid\Uuid;

#[AsMessageHandler]
class AddPaymentHandler
{
    public function __construct(
        private readonly PaymentRepository $paymentRepository,
        private readonly LoggerInterface   $logger
    ) {
    }

    public function __invoke(AddPayment $command): void
    {
        $paymentArray = $command->paymentArray();
        try {
            $currency = new Currency($paymentArray[1]);
            $payment = new Payment(
                Uuid::fromString($paymentArray[0]),
                $currency,
                new Money(intval(floatval($paymentArray[2]) * 100), $currency),
                new CustomerId(Uuid::fromString($paymentArray[3])),
            );
            $this->paymentRepository->save($payment);
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage());
        }

    }

}
