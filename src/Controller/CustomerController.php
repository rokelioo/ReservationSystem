<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\SpecialistRepository;
use App\Repository\ReservationRepository;
use App\Service\ReservationService;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;

class CustomerController extends AbstractController
{
    private $entityManager;
    private $reservationService;
    
    public function __construct(ReservationService $reservationService, EntityManagerInterface $entityManager)
    {
        $this->reservationService = $reservationService;
        $this->entityManager = $entityManager;
    }

    #[Route('/customer/reservation', name: 'customer_reservation')]
    public function Reservation(Request $request, SpecialistRepository $specialistRepository,  ReservationService $reservationService)
    {
        if($request->isMethod('POST'))
        {
            $data = [
                'name' => $request->request->get('name'),
                'surname' => $request->request->get('surname'),
                'specialistId' => $request->request->get('specialistId')
            ];
    
            try {
                $reservationCode = $reservationService->createReservation($data);
                $this->addFlash('success', 'Your reservation is confirmed. Your code is ' . $reservationCode . '.');
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }
        $specialists = $specialistRepository->findAllSpecialists();
        return $this->render('customer/reservation.html.twig', [
           'specialists'=>$specialists
        ]);
    }
    #[Route('/customer/visit', name: 'customer_visit')]
    public function checkVisit(Request $request, ReservationRepository $reservationRepository, CustomerRepository $customerRepository)
    {
        if($request->isMethod('POST'))
        {
            $code = $request->request->get('reservationCode');
            $customer = $customerRepository->findOneBy(['reservationCode' => $code]);
            if (!$customer) {
                return $this->renderWithError('Invalid Reservation Code.');
            }

            $reservation = $reservationRepository->findOneBy(['fkCustomer' => $customer->getPkId()]);
            if (!$reservation) {
                return $this->renderWithError('Reservation not found for given code.');
            }

            $remainingTime = $this->reservationService->getRemainingTime($reservation->getStartTime());

            return $this->render('customer/visit.html.twig', [
                'reservation' => $reservation,
                'remainingTime' => $remainingTime
            ]);
        }
        return $this->render('customer/visit.html.twig');
        
    }
    #[Route('/cancel/reservation/{id}', name: 'cancel_reservation')]
    public function cancelReservation(int $id, ReservationRepository $reservationRepository): Response
    {
            $reservation = $reservationRepository->find($id);
        if ($reservation) {
            $reservation->setStatus("canceled");
            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

            $this->addFlash('canceled', 'Your reservation has been cancelled.');
        } else {
            $this->addFlash('error', 'Reservation not found.');
        }

        return $this->redirectToRoute('customer_visit'); 
    }
    private function renderWithError($message) {
        return $this->render('customer/visit.html.twig', [
            'error' => $message
        ]);
    } 
}