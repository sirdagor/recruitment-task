<?php

declare(strict_types=1);

namespace spec\App\Invoice\Domain\Entity;

use App\Invoice\Domain\Entity\CustomerId;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;

class CustomerIdSpec extends ObjectBehavior
{
    public const CUSTOMER_ID = '0645f987-8858-489e-a9f8-1eb76672dc41';

    public function let(): void
    {
        $this->beConstructedWith(Uuid::fromString(self::CUSTOMER_ID));
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(CustomerId::class);
    }

    public function it_has_customerId(): void
    {
        $this->customerId()->shouldBeLike(Uuid::fromString(self::CUSTOMER_ID));
    }
}