<?php

declare(strict_types=1);

namespace App\Invoice\Domain\Repository;

use App\Invoice\Domain\Entity\Customer;
use App\Invoice\Domain\Entity\CustomerId;
use Ramsey\Uuid\UuidInterface;

interface CustomerRepository
{
    public function save(Customer $customer): void;

    /**
     * @param string[] $criteria
     * @param int $limit
     * @param int $offset
     * @return Customer[]
     */
    public function findPaginated(array $criteria, int $limit, int $offset): array;
}
