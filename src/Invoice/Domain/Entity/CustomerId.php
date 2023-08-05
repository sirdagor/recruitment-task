<?php

declare(strict_types=1);

namespace App\Invoice\Domain\Entity;

use Ramsey\Uuid\UuidInterface;

class CustomerId
{
    private UuidInterface $customerId;

    public function __construct(UuidInterface $customerId)
    {
        $this->customerId = $customerId;
    }

    public function customerId(): UuidInterface
    {
        return $this->customerId;
    }

    public function __toString(): string
    {
        return $this->customerId->toString();
    }

    public function toString(): string
    {
        return $this->customerId->toString();
    }

}
