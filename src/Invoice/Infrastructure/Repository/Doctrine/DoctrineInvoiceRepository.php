<?php

declare(strict_types=1);

namespace App\Invoice\Infrastructure\Repository\Doctrine;

use App\Invoice\Domain\Entity\CustomerId;
use App\Invoice\Domain\Entity\Invoice;
use App\Invoice\Domain\Exception\ObjectNotFoundException;
use App\Invoice\Domain\Repository\InvoiceRepository;
use App\Invoice\Domain\Type;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineInvoiceRepository extends ServiceEntityRepository implements InvoiceRepository
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
        parent::__construct($registry, Invoice::class);
    }

    public function save(Invoice $invoice): void
    {
        $manager = $this->registry->getManager();
        if ($manager->isOpen() === false) {
            $manager = $this->registry->resetManager();
        }
        $manager->persist($invoice);
        $manager->flush();
    }

    public function getByCustomerIdAndType(CustomerId $customerId, Type $type): array
    {
        return $this->findBy(
            [
                'customerId' => $customerId->customerId()->toString(),
                'type' => $type
            ],
            [
                'createdAt' => 'asc'
            ]
        ) ?? throw new ObjectNotFoundException();
    }

    public function getByType(Type $type): array
    {
        return $this->findBy([
            'type' => $type
        ]);
    }
}
