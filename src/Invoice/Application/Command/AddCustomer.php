<?php

namespace App\Invoice\Application\Command;

class AddCustomer
{
    public function __construct(
        private readonly string $customerId
    ) {
    }

    /**
     * @return string
     */
    public function customerId(): string
    {
        return $this->customerId;
    }
}
