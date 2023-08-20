<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }
    public function findAllBySpecialist($specialistId)
    {

        // Start of today
        $startOfToday = new \DateTime("midnight");
        
        // End of today
        $endOfToday = (clone $startOfToday)->modify('+1 day');
        $qb = $this->createQueryBuilder('r');
        $result = $qb->where('r.fkSpecialist = :specialistId')
            ->andWhere('r.starttime >= :start')
            ->andWhere('r.starttime < :end')
            ->andWhere('r.status NOT IN(:excludedStatuses)')
            ->setParameter('specialistId', $specialistId)
            ->setParameter('start', $startOfToday)
            ->setParameter('end', $endOfToday)
            ->setParameter('excludedStatuses', ['Cancel', 'End'])
            ->orderBy('r.starttime', 'ASC')
            ->getQuery()
            ->getResult();

        return $result; 
    }
    public function findLastReservation($specialistId, \DateTime $today)
    {
        // Define the start and end of today
        $startOfDay = clone $today;
        $startOfDay->setTime(0, 0, 0);
        
        $endOfDay = clone $today;
        $endOfDay->setTime(23, 59, 59);

        $db = $this->createQueryBuilder('r');

        $result = $db->where('r.fkSpecialist = :specialist')
            ->andWhere('r.starttime >= :startOfDay')  
            ->andWhere('r.endtime <= :endOfDay')      
            ->orderBy('r.endtime', 'DESC')
            ->setMaxResults(1)
            ->setParameter('specialist', $specialistId)
            ->setParameter('startOfDay', $startOfDay)
            ->setParameter('endOfDay', $endOfDay)
            ->getQuery()
            ->getOneOrNullResult();

        return $result;
    }
}