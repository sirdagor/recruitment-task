<?php

declare(strict_types=1);

namespace App\Invoice\Domain\Exception;

use Throwable;

class ClientNotFoundException extends \RuntimeException
{
    public function __construct(string $message = "", int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
