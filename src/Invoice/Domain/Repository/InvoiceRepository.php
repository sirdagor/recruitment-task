<?php

declare(strict_types=1);

namespace App\Invoice\Domain\Repository;

use App\Invoice\Domain\Entity\CustomerId;
use App\Invoice\Domain\Entity\Invoice;
use App\Invoice\Domain\Type;

interface InvoiceRepository
{
    /**
     * @return Invoice[]
     */
    public function getByCustomerIdAndType(CustomerId $customerId, Type $type): array;

    public function save(Invoice $invoice): void;

}
