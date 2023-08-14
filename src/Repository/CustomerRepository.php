<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }
    public function findAllBySpecialist($specialistId)
    {
        $qb = $this->createQueryBuilder('c');
        $result = $qb->where('c.fkSpecialist = :specialistId')
        ->setParameter('specialistId', $specialistId)
        ->getQuery()
        ->getResult();

        return $result; 
    }
}