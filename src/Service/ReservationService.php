<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\Customer;
use App\Repository\ReservationRepository;
use App\Repository\SpecialistRepository;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class ReservationService{

    private $entityManager;
    private $reservationRepository;
    private $specialistRepository;
    private $customerRepository;

    public function __construct(EntityManagerInterface $entityManager, ReservationRepository $reservationRepository, SpecialistRepository $specialistRepository, CustomerRepository $customerRepository) {
        $this->entityManager = $entityManager;
        $this->reservationRepository = $reservationRepository;
        $this->specialistRepository = $specialistRepository;
        $this->customerRepository = $customerRepository;
    }


    private function findNextReservationSlot($specialistId): array
    {
        $currentDateTime = new DateTime('now');
        

        $lastReservation = $this->reservationRepository->findLastReservation($specialistId, $currentDateTime);


        if ($lastReservation && $lastReservation->getEndTime() > $currentDateTime) {
            // If there is a reservation and it is ongoing or in the future
            $nextSlotStart = clone $lastReservation->getEndTime();
        } else {
            // If there is no reservation or the last reservation has already ended
            $nextSlotStart = clone $currentDateTime;
        }

        $nextSlotEnd = clone $nextSlotStart;
        $nextSlotEnd->modify('+15 minutes');

        return [
            'start' => $nextSlotStart,
            'end' => $nextSlotEnd
        ];
    }
    public function createReservation($data) {

        $specialist = $this->fetchSpecialist($data['specialistId']);
        if (!$specialist) {
            throw new \Exception("Specialist not found.");
        }

        $nextSlotTimes = $this->findNextReservationSlot($data['specialistId']);
        $reservationCode = $this->generateReservationCode();
        $customer = $this->createCustomer($data, $reservationCode, $specialist);

        $this->createAndPersistReservation($customer, $specialist, $nextSlotTimes);

        return $reservationCode;
    }
    private function fetchSpecialist($specialistId) {
        return $this->specialistRepository->findSpecialist($specialistId);
    }

    private function generateReservationCode() {
        return str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function createCustomer($data, $reservationCode, $specialist) {
        $customer = new Customer();
        $customer->setfkSpecialist($data['specialistId']);
        $customer->setName($data['name']);
        $customer->setSurname($data['surname']);
        $customer->setReservationCode($reservationCode);

        $this->entityManager->persist($customer);
        $this->entityManager->flush();
        return $customer;
    }

    private function createAndPersistReservation($customer, $specialist, $nextSlotTimes) {
        $reservation = new Reservation();
        $reservation->setfkSpecialist($specialist->getPkId());  
        $reservation->setfkCustomer($customer->getPkId()); 
        $reservation->setStartTime($nextSlotTimes['start']);
        $reservation->setEndTime($nextSlotTimes['end']);
        $reservation->setStatus("pending");

        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return $reservation;
    }
    public function getRemainingTime($startTime) {
        $now = new \DateTime('now');
    
        if ($now > $startTime) {
            return null;
        }
    
        $interval = $now->diff($startTime);
        return $interval->format('%h hours %i minutes');
    }
    public function sortReservations(array $reservations): array
    {
        usort($reservations, function ($a, $b) {
            if ($a->getStatus() === 'Begin' && $b->getStatus() !== 'Begin') {
                return -1;
            } elseif ($b->getStatus() === 'Begin' && $a->getStatus() !== 'Begin') {
                return 1;
            } elseif ($a->getStatus() === 'Priority' && $b->getStatus() !== 'Priority') {
                return -1;
            } elseif ($b->getStatus() === 'Priority' && $a->getStatus() !== 'Priority') {
                return 1;
            }
            return 0;
        });

        return $reservations;
    }

    public function getReservationDetails(array $sortedReservations, CustomerRepository $customerRepository): array
    {
        $ongoingReservation = null;
        $reservationCustomers = [];
        
        foreach ($sortedReservations as $reservation) {
            $customer = $customerRepository->find($reservation->getFkCustomer());
            if ($reservation->getStatus() == 'Begin' && !$ongoingReservation) {
                $ongoingReservation = [
                    'customer' => $customer,
                    'reservation' => $reservation
                ];
            }
            $reservationCustomers[] = [
                'customer' => $customer,
                'reservation' => $reservation
            ];
        }

        return [
            'all' => $reservationCustomers,
            'ongoing' => $ongoingReservation
        ];
    }
}
