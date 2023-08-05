<?php

declare(strict_types=1);

namespace App\Invoice\Application\Command;

class AddInvoice
{
    /**
     * @param string[] $invoiceArray
     */
    public function __construct(
        private readonly array $invoiceArray
    ) {
    }

    /**
     * @return string[]
     */
    public function invoiceArray(): array
    {
        return $this->invoiceArray;
    }
}
