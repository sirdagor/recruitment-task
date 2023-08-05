<?php

namespace App\Invoice\Application\DTO;

use App\Invoice\Domain\Entity\CustomerId;

class AccountSummary
{
    /**
     * @param CustomerId $customerId
     * @param string $lastInvoiceNumber
     * @param BalanceView[] $balances
     * @param InvoiceView[] $invoices
     */
    public function __construct(
        private readonly CustomerId $customerId,
        private readonly string $lastInvoiceNumber,
        private readonly array $balances,
        private readonly array $invoices
    ) {
    }

    /**
     * @return InvoiceView[]
     */
    public function invoices(): array
    {
        return $this->invoices;
    }

    /**
     * @return BalanceView[]
     */
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
