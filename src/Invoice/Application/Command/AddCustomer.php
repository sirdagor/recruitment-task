<?php

namespace App\Invoice\Application\Command;

class AddCustomer
{
    public function __construct(
        private readonly string $customerId
    ) {
    }

    /**
     * @return mixed
     */
    public function customerId(): string
    {
        return $this->customerId;
    }
}
