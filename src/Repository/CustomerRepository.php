<?php

namespace App\Repository;

use App\Entity\Main\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function findBySubdomain(string $subdomain): ?Customer
    {
        try {
            $queryBuilder = $this->createQueryBuilder('c')
                ->andWhere('c.subdomain = :subdomain')
                ->setParameter('subdomain', $subdomain)
                ->getQuery()
            ;

            return $queryBuilder->getOneOrNullResult();
        } catch (NonUniqueResultException $exception) {
            return null;
        }

    }
}
