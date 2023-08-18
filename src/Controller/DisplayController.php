<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CustomerRepository;
use App\Repository\ReservationRepository;

class DisplayController extends AbstractController
{
    #[Route('/display/{id}', name: 'display_board')]
    public function showReservations($id, ReservationRepository $reservationRepository, CustomerRepository $customerRepository)
    {
        // Fetch all of today's reservations for the given specialist id.
        $startDate = new \DateTime('midnight');
        $endDate = (clone $startDate)->modify('+1 day');
        
        $query = $reservationRepository->createQueryBuilder('r')
            ->where('r.fkSpecialist = :id')
            ->andWhere('r.starttime BETWEEN :startDate AND :endDate')
            ->andWhere('r.status != :cancelledStatus')
            ->setParameter('id', $id)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('cancelledStatus', 'Cancel')
            ->getQuery();

        $reservations = $query->getResult();

        // Sort reservations by priority status.
        usort($reservations, function ($a, $b) {
            if ($a->getStatus() === 'Priority') {
                return -1;
            } elseif ($b->getStatus() === 'Priority') {
                return 1;
            }
            return 0;
        });

        // Fetch the reservation code for each reservation's associated customer.
        $reservationCodes = [];
        foreach ($reservations as $reservation) {
            $customer = $customerRepository->find($reservation->getFkCustomer());
            $reservationCodes[] = $customer->getReservationCode();
        }

        // Only take the next 7 reservations.
        $nextReservations = array_slice($reservationCodes, 0, 7);

        return $this->render('display/board.html.twig', [
            'reservationCodes' => $nextReservations
        ]);
    }
}