<?php

declare(strict_types=1);

namespace spec\App\Invoice\Domain\Entity;

use App\Invoice\Domain\Entity\CustomerId;
use App\Invoice\Domain\Entity\Payment;
use App\Invoice\Domain\Exception\ValidationException;
use Money\Currency;
use Money\Money;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;

class PaymentSpec extends ObjectBehavior
{
    public const CUSTOMER_ID = '0645f987-8858-489e-a9f8-1eb76672dc41';
    public const ID = 'ee548085-fa23-4a78-a870-6f8357b7964b';

    public function let(): void
    {
        $currency = new Currency('USD');
        $this->beConstructedWith(
            Uuid::fromString(self::ID),
            $currency,
            new Money(100, $currency),
            new CustomerId(Uuid::fromString(self::CUSTOMER_ID))
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Payment::class);
    }

    public function it_has_valid_data()
    {
        $this->id()->shouldBeLike(Uuid::fromString(self::ID));
        $this->currency()->shouldBeLike(new Currency('USD'));
        $this->customerId()->shouldBeLike(new CustomerId(Uuid::fromString(self::CUSTOMER_ID)));
        $this->amount()->shouldBeLike(new Money(100, new Currency('USD')));
    }

    public function it_should_validate_currency()
    {
        $currency = new Currency('USDD');
        $this->beConstructedWith(
            Uuid::fromString(self::ID),
            $currency,
            new Money(100, $currency),
            new CustomerId(Uuid::fromString(self::CUSTOMER_ID))
        );
        $this->shouldThrow(ValidationException::class);
    }
}