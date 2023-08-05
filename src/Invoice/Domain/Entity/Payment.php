<?php

declare(strict_types=1);

namespace App\Invoice\Domain\Entity;

use App\Invoice\Domain\Exception\ValidationException;
use Money\Currency;
use Money\Money;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'payment')]
class Payment
{
    #[Id]
    #[Column(name: "id", type: "uuid", length: 36, nullable: false, options: ["fixed" => true])]
    private UuidInterface $id;

    #[Column(name: "customer_id", type: "customerId", length: 36, nullable: false, options: ["fixed" => true])]
    private CustomerId $customerId;

    #[Column(name: "amount", type: "money", length: 255, nullable: false)]
    private Money $amount;

    #[Column(name: "currency", type: "currency", length: 3, nullable: false)]
    private Currency $currency;

    /**
     * @param UuidInterface $id
     * @param CustomerId $customerId
     * @param Money $amount
     * @param Currency $currency
     */
    public function __construct(
        UuidInterface $id,
        Currency $currency,
        Money $amount,
        CustomerId $customerId,
    ) {
        if (strlen($currency->getCode()) !== 3) {
            throw new ValidationException("Currency code: is not valid or not valid with ISO");
        }

        $this->id = $id;
        $this->customerId = $customerId;
        $this->amount = $amount;
        $this->currency = $currency;
    }


    /**
     * @return UuidInterface
     */
    public function id(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return Currency
     */
    public function currency(): Currency
    {
        return $this->currency;
    }

    /**
     * @return Money
     */
    public function amount(): Money
    {
        return $this->amount;
    }

    /**
     * @return CustomerId
     */
    public function customerId(): CustomerId
    {
        return $this->customerId;
    }
}
