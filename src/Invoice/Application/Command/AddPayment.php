<?php

declare(strict_types=1);

namespace App\Invoice\Application\Command;

class AddPayment
{
    /**
     * @param string[] $paymentArray
     */
    public function __construct(
        private readonly array $paymentArray
    ) {
    }

    /**
     * @return string[]
     */
    public function paymentArray(): array
    {
        return $this->paymentArray;
    }
}
