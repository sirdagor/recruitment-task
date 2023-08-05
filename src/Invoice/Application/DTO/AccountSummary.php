<?php

namespace App\Invoice\Application\DTO;

use App\Invoice\Domain\Entity\CustomerId;

class AccountSummary
{
    public function __construct(
        private readonly CustomerId $customerId,
        private readonly string $lastInvoiceNumber,
        private readonly array $balances,
        private readonly array $invoices
    ) {
    }

    /**
     * @return array
     */
    public function invoices(): array
    {
        return $this->invoices;
    }

    public function balances(): array
    {
        return $this->balances;
    }

    /**
     * @return CustomerId
     */
    public function customerId(): CustomerId
    {
        return $this->customerId;
    }

    /**
     * @return string
     */
    public function lastInvoiceNumber(): string
    {
        return $this->lastInvoiceNumber;
    }

}
