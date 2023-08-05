<?php

namespace App\Invoice\Application\DTO;

class AccountSummaryView implements \JsonSerializable
{
    public function __construct(private readonly array $accountSummary)
    {

    }

    /**
     * @return string[]
     */
    public function toArray(): array
    {
        $items = [];
        foreach ($this->accountSummary as $item) {
            $items[] = [
                'customerId' => (string)$item->customerId(),
                'balances' => $item->balances(),
                'lastInvoiceNumber' => $item->lastInvoiceNumber(),
                'invoices' => $item->invoices()
            ];
        }

        return $items;
    }

    /**
     * @return string[]
     */
    public function jsonSerialize(): array
    {
        $items = [];
        foreach ($this->accountSummary as $item) {
            $items[] = [
                'customerId' => (string)$item->customerId(),
                'balances' => $item->balances(),
                'lastInvoiceNumber' => $item->lastInvoiceNumber(),
                'invoices' => $item->invoices()
            ];
        }

        return $items;
    }
}
