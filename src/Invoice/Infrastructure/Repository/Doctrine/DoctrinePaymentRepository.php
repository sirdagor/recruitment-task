<?php

declare(strict_types=1);

namespace App\Invoice\Infrastructure\Repository\Doctrine;

use App\Invoice\Domain\Entity\CustomerId;
use App\Invoice\Domain\Entity\Payment;
use App\Invoice\Domain\Exception\ObjectNotFoundException;
use App\Invoice\Domain\Repository\PaymentRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrinePaymentRepository extends ServiceEntityRepository implements PaymentRepository
{
    private ManagerRegistry $registry;
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
        parent::__construct($registry, Payment::class);
    }

    public function save(Payment $payment): void
    {
        $manager = $this->registry->getManager();
        if (!$manager->isOpen()) {
            $manager = $manager->create(
                $manager->getConnection(),
                $manager->getConfiguration()
            );
        }
        $manager->persist($payment);
        $manager->flush();
    }

    public function getByCustomerId(CustomerId $customerId): array
    {
        return $this->findBy([
            'customerId' => $customerId->customerId()->toString(),
        ]) ?? throw new ObjectNotFoundException();
    }
}
