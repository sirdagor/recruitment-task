<?php

declare(strict_types=1);

namespace App\Invoice\Domain\Entity;

enum InvoiceType: string
{
    case INVOICE = 'invoice';
    case DRAFT = 'draft';
}
