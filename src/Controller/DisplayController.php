<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CustomerRepository;
use App\Repository\ReservationRepository;
use App\Service\ReservationService;

class DisplayController extends AbstractController
{

    private $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    #[Route('/display/{id}', name: 'display_board')]
    public function showReservations($id, ReservationRepository $reservationRepository, CustomerRepository $customerRepository)
    {
        
        $reservations = $reservationRepository->findAllBySpecialist($id);
        $sortedReservations = $this->reservationService->sortReservations($reservations);
        $nextReservations = $this->getReservationDetails($sortedReservations, $customerRepository);

        return $this->render('display/board.html.twig', [
            'reservationCodes' => $nextReservations,
            'specialist_id' => $id
        ]);
    }

    #[Route('/api/display/{id}', name: 'api_display_board')]
    public function apiShowReservations($id, ReservationRepository $reservationRepository, CustomerRepository $customerRepository)
    {
         
         $reservations = $reservationRepository->findAllBySpecialist($id);
         $sortedReservations = $this->reservationService->sortReservations($reservations);
         $nextReservations = $this->getReservationDetails($sortedReservations, $customerRepository);

        return $this->json($nextReservations);
    }


    private function getReservationDetails(array $reservations, CustomerRepository $customerRepository): array
    {
        $reservationDetails = [];
        foreach ($reservations as $reservation) {
            $customer = $customerRepository->find($reservation->getFkCustomer());
            $reservationDetails[] = [
                'code' => $customer->getReservationCode(),
                'status' => $reservation->getStatus()
            ];
        }

        return array_slice($reservationDetails, 0, 8);
    }

}