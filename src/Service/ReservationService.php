<?php

namespace App\Service;

use App\Entity\Reservation;
use App\Entity\Specialist;
use App\Repository\ReservationRepository;
use DateTime;

class ReservationService{
    public function findNextReservationSlot($specialistId, ReservationRepository $reservationRepository): array
    {
        $currentDateTime = new DateTime('now');
        

    $lastReservation = $reservationRepository->findLastReservation($specialistId, $currentDateTime);


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
}