<?php

declare(strict_types=1);

namespace spec\App\Invoice\Domain\Entity;

use App\Invoice\Domain\Entity\CustomerId;
use App\Invoice\Domain\Entity\Invoice;
use App\Invoice\Domain\Entity\Payment;
use App\Invoice\Domain\Exception\ValidationException;
use App\Invoice\Domain\Type;
use Money\Currency;
use Money\Money;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;

class InvoiceSpec extends ObjectBehavior
{
    public const CUSTOMER_ID = '0645f987-8858-489e-a9f8-1eb76672dc41';
    public const ID = 'ee548085-fa23-4a78-a870-6f8357b7964b';
    public function let(): void
    {
        $currency = new Currency('USD');
        $this->beConstructedWith(
            Uuid::fromString(self::ID),
            Type::INVOICE,
            new CustomerId(Uuid::fromString(self::CUSTOMER_ID)),
            'DP/2004/08/01/54827348',
            new Money(100, $currency),
            new Money(200, $currency),
            new Money(300, $currency),
            $currency,
            new \DateTimeImmutable('2027-05-19')
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Invoice::class);
    }

    public function it_has_valid_data()
    {
        $this->id()->shouldBeLike(Uuid::fromString(self::ID));
        $this->customerId()->shouldBeLike(new CustomerId(Uuid::fromString(self::CUSTOMER_ID)));
        $this->number()->shouldBeLike('DP/2004/08/01/54827348');
        $this->nettAmount()->shouldBeLike(new Money(100, new Currency('USD')));
        $this->grossAmount()->shouldBeLike(new Money(200, new Currency('USD')));
        $this->taxAmount()->shouldBeLike(new Money(300, new Currency('USD')));
        $this->currency()->shouldBeLike(new Currency('USD'));
        $this->createdAt()->shouldBeLike(new \DateTimeImmutable('2027-05-19'));
    }

    public function it_should_validate_currency()
    {
        $currency = new Currency('USDD');
        $this->beConstructedWith(
            Uuid::fromString(self::ID),
            Type::INVOICE,
            new CustomerId(Uuid::fromString(self::CUSTOMER_ID)),
            'DP/2004/08/01/54827348',
            new Money(100, $currency),
            new Money(200, $currency),
            new Money(300, $currency),
            $currency,
            new \DateTimeImmutable('2027-05-19')
        );
        $this->shouldThrow(ValidationException::class);
    }

    public function it_should_validate_invoice_number()
    {
        $currency = new Currency('USD');
        $this->beConstructedWith(
            Uuid::fromString(self::ID),
            Type::INVOICE,
            new CustomerId(Uuid::fromString(self::CUSTOMER_ID)),
            'DP_DRAFT/2004/08/01/54827348',
            new Money(100, $currency),
            new Money(200, $currency),
            new Money(300, $currency),
            $currency,
            new \DateTimeImmutable('2027-05-19')
        );
        $this->shouldThrow(ValidationException::class);
    }
}