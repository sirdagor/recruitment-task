<?php

declare(strict_types=1);

namespace App\Invoice\Domain\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Ramsey\Uuid\UuidInterface;

#[Entity]
#[Table(name: 'customer')]
class Customer
{
    #[Id]
    #[Column(name: "id", type: "customerId", length: 36, unique: true, nullable: false, options: ["fixed" => true])]
    private CustomerId $id;

    public function __construct(CustomerId $id)
    {
        $this->id = $id;
    }

    public function id(): CustomerId
    {
        return $this->id;
    }
}
