<?php

namespace App\Repository;

use App\Entity\Specialist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SpecialistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Specialist::class);
    }

    public function findByCredentials($name, $password)
    {
        $qb = $this->createQueryBuilder('s');

        $result = $qb->select('s.pkId, s.surname')
                     ->where('s.name = :name')
                     ->andWhere('s.password = :password')
                     ->setParameter('name', $name)
                     ->setParameter('password', $password)
                     ->getQuery()
                     ->getOneOrNullResult();

        return $result;             
    }
    public function findAllSpecialists()
    {
        $qb = $this->createQueryBuilder('s');

        $result = $qb->select('s.pkId, s.name, s.surname')
                     ->getQuery()
                     ->getResult();

        return $result;             
    }
    public function findSpecialist($specialistId)
    {
        return $this->findOneBy(['pkId' => $specialistId]);    
    }

}