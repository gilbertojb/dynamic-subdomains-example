<?php

namespace App\Repository;

use App\Entity\Customer\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function findAll(): array
    {
        $queryBuilder = $this->createQueryBuilder('e')->getQuery();

        return $queryBuilder->getArrayResult();
    }
}
