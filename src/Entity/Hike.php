<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation as ApiPlatform;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HikeRepository")
 * @UniqueEntity(fields={"name", "event"}, message="Hike name must be unique for this event")
 * @ApiPlatform\ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"},
 *     attributes={
 *         "normalization_context"={"groups"={"hike"}}
 *     }
 * )
 */
class Hike
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
     * @var Event
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="hikes")
     */
    private $event;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $maxWalkers;

    /**
     * @ORM\OneToMany(targetEntity="Team", mappedBy="hike")
     * @var Collection
     */
    private $teams;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name . " » " . $this->event->__toString();
    }

    /**
     * @Groups({"hike"})
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @Groups({"hike"})
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
     * @Groups({"hike"})
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    /**
     * @Groups({"hike"})
     * @return Event|null
     */
    public function getEvent(): ?Event
    {
        return $this->event;
    }

    /**
     * @param Event $event
     */
    public function setEvent(Event $event): void
    {
        $this->event = $event;
    }

    /**
     * @Groups({"hike"})
     * @return int|null
     */
    public function getMaxWalkers(): ?int
    {
        return $this->maxWalkers;
    }

    /**
     * @param int $maxWalkers
     */
    public function setMaxWalkers(int $maxWalkers)
    {
        $this->maxWalkers = $maxWalkers;
    }
}
