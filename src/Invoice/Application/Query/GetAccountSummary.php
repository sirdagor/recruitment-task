<?php

declare(strict_types=1);

namespace App\Invoice\Application\Query;

use App\Invoice\Domain\Entity\CustomerId;

class GetAccountSummary
{
    public function __construct(
        public readonly int         $limit,
        public readonly int         $offset,
        public readonly ?CustomerId $customerId,
    ) {
    }

    /**
     * @return int
     */
    public function limit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function offset(): int
    {
        return $this->offset;
    }


    public function customerId(): ?CustomerId
    {
        return $this->customerId;
    }
}
