<?php

namespace App\Invoice\Infrastructure\Repository\Doctrine;

use App\Invoice\Domain\Entity\Customer;
use App\Invoice\Domain\Exception\ObjectNotFoundException;
use App\Invoice\Domain\Repository\CustomerRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineCustomerRepository extends ServiceEntityRepository implements CustomerRepository
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
        parent::__construct($registry, Customer::class);
    }

    public function save(Customer $customer): void
    {
        /** @var \Doctrine\ORM\EntityManagerInterface */
        $manager = $this->registry->getManager();
        if ($manager->isOpen() === false) {
            $manager = $this->registry->resetManager();
        }
        $existingCustomer = $this->findOneBy(['id' => $customer->id()->toString()]);
        if (!$existingCustomer) {
            $manager->persist($customer);
            $manager->flush();
        }
    }

    /**
     * @param mixed $criteria
     * @param int $limit
     * @param int $offset
     * @return Customer[]
     */
    public function findPaginated(array $criteria, int $limit, int $offset): array
    {
        return $this->findBy($criteria, null, $limit, $offset);
    }
}
