<?php

declare(strict_types=1);

namespace App\Invoice\Domain\Repository;

use App\Invoice\Domain\Entity\CustomerId;
use App\Invoice\Domain\Entity\Payment;

interface PaymentRepository
{
    public function save(Payment $payment): void;


    /**
     * @return Payment[]
     */
    public function getByCustomerId(CustomerId $customerId): array;
}
