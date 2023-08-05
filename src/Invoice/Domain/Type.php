<?php

declare(strict_types=1);

namespace App\Invoice\Domain;

enum Type: string
{
    case DRAFT = 'draft';
    case INVOICE = 'invoice';
}
