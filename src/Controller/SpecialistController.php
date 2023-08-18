<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\SpecialistRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\CustomerRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;

class SpecialistController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/specialist/login', name: 'specialist_login')]
    public function Login(Request $request, SpecialistRepository $specialistRepository, SessionInterface $session): Response
    {
        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $password = $request->request->get('password');
    
            $specialist = $specialistRepository->findByCredentials($name, $password);
    
            if ($specialist) {
                $pkId = $specialist['pkId'];
                $surname = $specialist['surname'];

                $session->set('pkId', $pkId);

                return $this->redirectToRoute('specialist_main'); 
            } else {
                $this->addFlash('error', 'Invalid credentials!');
            }
        }

        return $this->render('specialist/login.html.twig');
    }

    #[Route('/specialist/main', name: 'specialist_main')]
    public function Main(SessionInterface $session, CustomerRepository $customerRepository, ReservationRepository $reservationRepository)
    {
        $pkId = $session->get('pkId');
        $customers = $customerRepository->findAllBySpecialist($pkId);

        $customerReservations = [];
        foreach ($customers as $customer) {
            $reservation = $reservationRepository->findOneBy(['fkCustomer' => $customer->getPkId()]);
            $customerReservations[] = [
                'customer' => $customer,
                'reservation' => $reservation
            ];
        }

        return $this->render('specialist/main.html.twig',[
            'customerReservations' => $customerReservations
        ]);
    }
    #[Route('/update/reservation/status/{id}', name: 'update_reservation_status', methods: ["POST"])]
    public function updateStatus(int $id, Request $request, ReservationRepository $reservationRepository): Response
    {
        $status = $request->request->get('status');
        $reservation = $reservationRepository->find($id);
        if ($reservation) {
            $reservation->setStatus($status);
            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

            $this->addFlash('success', 'Reservation status updated.');
        } else {
            $this->addFlash('error', 'Reservation not found.');
        }

        return $this->redirectToRoute('specialist_main'); 
    }
}