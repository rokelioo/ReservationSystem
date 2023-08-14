<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customer
 *
 * @ORM\Table(name="customer")
 * @ORM\Entity
 */
class Customer
{

    /**
     * @var int
     *
     * @ORM\Column(name="pk_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $pkId;

    /**
     * @var int
     *
     * @ORM\Column(name="fk_specialist", type="integer", nullable=false)
     */
    private $fkSpecialist;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=15, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=25, nullable=false)
     */
    private $surname;

    /**
     * @var int
     *
     * @ORM\Column(name="reservation_code", type="integer", nullable=false)
     */
    private $reservationCode;

    public function getName(): ?string
    {
        return $this->name;
    }
    public function getSurname(): ?string
    {
        return $this->surname;
    }
    public function getreservationCode(): ?int
    {
        return $this->reservationCode;
    }
}
