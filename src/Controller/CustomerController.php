<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\SpecialistRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Service\ReservationService;
use App\Entity\Customer;
use App\Entity\Reservation;
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
    public function Reservation(Request $request, SpecialistRepository $specialistRepository, ReservationRepository $reservationRepository)
    {
        if($request->isMethod('POST'))
        {
            $name = $request->request->get('name');
            $surname = $request->request->get('surname');
            $specialistId = $request->request->get('specialistId');

            $specialist = $specialistRepository->findSpecialist($specialistId);

            if ($specialist) {
                $nextSlotTimes = $this->reservationService->findNextReservationSlot($specialist, $reservationRepository);
            
                // Generate a reservation code. This can be more sophisticated.
                $reservationCode = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT); // Just a simple example

                $customer = new Customer();
                $customer->setfkSpecialist($specialistId);
                $customer->setName($name);
                $customer->setSurname($surname);
                $customer->setReservationCode($reservationCode);

                $this->entityManager->persist($customer);
                $this->entityManager->flush();
                $customerId = $customer->getPkId();

                $reservation = new Reservation();
                $reservation->setfkSpecialist($specialistId);
                $reservation->setfkCustomer($customerId);
                $reservation->setStartTime($nextSlotTimes['start']);
                $reservation->setEndTime($nextSlotTimes['end']);
                $reservation->setStatus("pending");

                $this->entityManager->persist($reservation);
                $this->entityManager->flush();

                $this->addFlash('success', 'Your reservation is confirmed from ' 
                    . $nextSlotTimes['start']->format('H:i') 
                    . ' to ' 
                    . $nextSlotTimes['end']->format('H:i') 
                    . '. Your code is ' . $reservationCode . '.');
            } else {
                $this->addFlash('error', 'Specialist not found.');
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
            $reservation = null;
            if ($customer) {
                $reservation = $reservationRepository->findOneBy(['fkCustomer' => $customer->getPkId()]);
            }

            if ($reservation) {
                $now = new \DateTime('now');
                $interval = $now->diff($reservation->getStartTime());
                $remainingTime = $interval->format('%h hours %i minutes');

                return $this->render('customer/visit.html.twig', [
                    'reservation' => $reservation,
                    'remainingTime' => $remainingTime
                ]);
            } 
            else {
                return $this->render('customer/visit.html.twig', [
                    'invalidCode' => true
                ]);
            }
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

            $this->addFlash('success', 'Your reservation has been cancelled.');
        } else {
            $this->addFlash('error', 'Reservation not found.');
        }

        return $this->redirectToRoute('customer_visit'); 
    }
}