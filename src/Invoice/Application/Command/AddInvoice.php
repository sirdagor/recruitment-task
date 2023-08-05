<?php

declare(strict_types=1);

namespace App\Invoice\Application\Command;

class AddInvoice
{
    public function __construct(
        private readonly array $invoiceArray
    ) {
    }

    /**
     * @return mixed
     */
    public function invoiceArray(): array
    {
        return $this->invoiceArray;
    }
}
