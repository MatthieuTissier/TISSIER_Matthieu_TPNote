<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 * @UniqueEntity(
 *     fields={"date", "timeSlot"},
 *     message="Cette plage horaire est déjà réservée pour cette date."
 * )
 */
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Assert\NotBlank()
     * @Assert\GreaterThan("today + 1 day")
     * @Assert\DateTime()
     */
    private ?\DateTimeImmutable $date = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private ?string $timeSlot = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private ?string $eventName = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user = null;

    // Getters et setters...

    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param \DateTimeImmutable $date
     */
    public function setDate(\DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return string
     */
    public function getTimeSlot(): ?string
    {
        return $this->timeSlot;
    }

    /**
     * @param string $timeSlot
     */
    public function setTimeSlot(string $timeSlot): self
    {
        $this->timeSlot = $timeSlot;

        return $this;
    }

    /**
     * @return string
     */
    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    /**
     * @param string $eventName
     */
    public function setEventName(string $eventName): self
    {
        $this->eventName = $eventName;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
