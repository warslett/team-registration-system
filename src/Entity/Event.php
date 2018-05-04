<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation as ApiPlatform;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 * @UniqueEntity(fields="name", message="Event name must be unique")
 * @ApiPlatform\ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"},
 *     attributes={
 *         "normalization_context"={"groups"={"event"}}
 *     }
 * )
 */
class Event
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=140)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    private $date;

    /**
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    private $registrationOpens;

    /**
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    private $registrationCloses;

    /**
     * @ORM\OneToMany(targetEntity="Hike", mappedBy="event")
     * @var Collection|Hike[]
     */
    private $hikes;

    public function __construct()
    {
        $this->hikes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @Groups({"event"})
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @Groups({"event"})
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(?string $name)
    {
        $this->name = $name;
    }

    /**
     * @Groups({"event"})
     * @return Hike[]|Collection
     */
    public function getHikes(): Collection
    {
        return $this->hikes;
    }

    /**
     * @Groups({"event"})
     * @return \DateTime
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getRegistrationOpens(): \DateTime
    {
        return $this->registrationOpens;
    }

    /**
     * @param \DateTime $registrationOpens
     */
    public function setRegistrationOpens(\DateTime $registrationOpens): void
    {
        $this->registrationOpens = $registrationOpens;
    }

    /**
     * @return \DateTime
     */
    public function getRegistrationCloses(): \DateTime
    {
        return $this->registrationCloses;
    }

    /**
     * @param \DateTime $registrationCloses
     */
    public function setRegistrationCloses(\DateTime $registrationCloses): void
    {
        $this->registrationCloses = $registrationCloses;
    }
}
