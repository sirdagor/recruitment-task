<?php

declare(strict_types=1);

namespace App\Invoice\Domain\Entity;

use App\Invoice\Domain\Exception\ValidationException;
use App\Invoice\Domain\Type;
use DateTimeImmutable;
use Money\Currency;
use Money\Money;
use Ramsey\Uuid\UuidInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Index;

#[Entity]
#[Table(name: 'invoice')]
#[Index(columns: ['created_at'], name: 'created_at')]
class Invoice
{
    #[Id]
    #[Column(name: "id", type: "uuid", length: 36, nullable: false, options: ["fixed" => true])]
    private UuidInterface $id;

    #[Column(name: "type", enumType: Type::class, length: 255, nullable: false)]
    private Type $type;

    #[Column(name: "customer_id", type: "customerId", length: 36, nullable: false, options: ["fixed" => true])]
    private CustomerId $customerId;

    #[Column(name: "number", type: "string", length: 255, nullable: false)]
    private string $number;
    #[Column(name: "nett_amount", type: "money")]
    private Money $nettAmount;

    #[Column(name: "gross_amount", type: "money")]
    private Money $grossAmount;

    #[Column(name: "tax_amount", type: "money")]
    private Money $taxAmount;

    #[Column(name: "currency", type: "currency", length: 3, nullable: false)]
    private Currency $currency;

    #[Column(name: "created_at", type: "datetime_immutable", nullable: false)]
    private DateTimeImmutable $createdAt;

    public const INVOICE_PATTERN = '/^DP\/(\d{4})\/(\d{2})\/(\d{2})\/\d{8}$/';
    public const DRAFT_PATTERN = '/^DP_DRAFT\/(\d{4})\/(\d{2})\/(\d{2})\/\d{8}$/';


    /***
     * @param UuidInterface $id
     * @param Type $type
     * @param CustomerId $customerId
     * @param string $number
     * @param Money $nettAmount
     * @param Money $grossAmount
     * @param Money $taxAmount
     * @param Currency $currency
     * @param DateTimeImmutable $createdAt
     */
    public function __construct(
        UuidInterface     $id,
        Type              $type,
        CustomerId        $customerId,
        string            $number,
        Money             $nettAmount,
        Money             $grossAmount,
        Money             $taxAmount,
        Currency          $currency,
        DateTimeImmutable $createdAt
    ) {

        if (strlen($currency->getCode()) !== 3) {
            throw new ValidationException("Currency code: is not valid or not valid with ISO");
        }

        if ($type === Type::INVOICE && !preg_match(self::INVOICE_PATTERN, $number)) {
            throw new ValidationException("Invoice number:$number is not valid");
        }

        if ($type === Type::DRAFT && !preg_match(self::DRAFT_PATTERN, $number)) {
            throw new ValidationException("Invoice Draft number:$number is not valid");
        }

        $this->id = $id;
        $this->type = $type;
        $this->customerId = $customerId;
        $this->number = $number;
        $this->nettAmount = $nettAmount;
        $this->grossAmount = $grossAmount;
        $this->taxAmount = $taxAmount;
        $this->currency = $currency;
        $this->createdAt = $createdAt;
    }

    /**
     * @return UuidInterface
     */
    public function id(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return Type
     */
    public function type(): Type
    {
        return $this->type;
    }

    /**
     * @return CustomerId
     */
    public function customerId(): CustomerId
    {
        return $this->customerId;
    }

    /**
     * @return string
     */
    public function number(): string
    {
        return $this->number;
    }


    public function nettAmount(): Money
    {
        return $this->nettAmount;
    }

    /**
     * @return Money
     */
    public function grossAmount(): Money
    {
        return $this->grossAmount;
    }

    /**
     * @return Money
     */
    public function taxAmount(): Money
    {
        return $this->taxAmount;
    }

    /**
     * @return Currency
     */
    public function currency(): Currency
    {
        return $this->currency;
    }

    /**
     * @return DateTimeImmutable
     */
    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }


}
