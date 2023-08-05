<?php

declare(strict_types=1);

namespace App\Invoice\Domain\Entity;

use Ramsey\Uuid\UuidInterface;

class CustomerId
{
    private UuidInterface $customerId;

    /**
     * @param UuidInterface $customerId
     */
    public function __construct(UuidInterface $customerId)
    {
        $this->customerId = $customerId;
    }

    /**
     * @return UuidInterface
     */
    public function customerId(): UuidInterface
    {
        return $this->customerId;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->customerId->toString();
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->customerId->toString();
    }

}
