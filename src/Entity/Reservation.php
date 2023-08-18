<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity
 */
class Reservation
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
     * @var int
     *
     * @ORM\Column(name="fk_customer", type="integer", nullable=false)
     */
    private $fkCustomer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startTime", type="datetime", nullable=false)
     */
    private $starttime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endTime", type="datetime", nullable=false)
     */
    private $endtime;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=15, nullable=false)
     */
    private $status;
    public function getpkId(): int
    {
        return $this->pkId;
    }
    public function getEndTime(): \DateTime
    {
        return $this->endtime;
    }
    public function getStartTime(): \DateTime
    {
        return $this->starttime;
    }
    public function getStatus(): string
    {
        return $this->status;
    }
    public function getFkCustomer(): int
    {
        return $this->fkCustomer;
    }


    public function setfkSpecialist(int $fkSpecialist): self
    {
        $this->fkSpecialist = $fkSpecialist;

        return $this;
    }
    public function setfkCustomer(int $fkCustomer): self
    {
        $this->fkCustomer = $fkCustomer;

        return $this;
    }

    public function setStartTime(\DateTime $starttime): self
    {
        $this->starttime = $starttime;

        return $this;
    }
    public function setEndTime(\DateTime $endtime): self
    {
        $this->endtime = $endtime;

        return $this;
    }
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

}
