<?php

declare(strict_types=1);

namespace App\Invoice\Application\Command;

class AddPayment
{
    public function __construct(
        private readonly array $paymentArray
    ) {
    }

    /**
     * @return mixed
     */
    public function paymentArray(): array
    {
        return $this->paymentArray;
    }
}
