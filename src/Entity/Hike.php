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
     * @var int|null
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=140)
     * @var string|null
     */
    private $name;

    /**
     * @var Event|null
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="hikes")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $event;

    /**
     * @var int|null
     * @ORM\Column(type="integer")
     */
    private $minWalkers;

    /**
     * @var int|null
     * @ORM\Column(type="integer")
     */
    private $maxWalkers;

    /**
     * @var float|null
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $feePerWalker;

    /**
     * @var float|null
     * @ORM\Column(type="decimal", precision=4, scale=2)
     */
    private $minAge;

    /**
     * @var float|null
     * @ORM\Column(type="decimal", precision=4, scale=2)
     */
    private $maxAge;

    /**
     * @ORM\OneToMany(targetEntity="Team", mappedBy="hike")
     * @var Collection
     */
    private $teams;

    /**
     * @var int|null
     * @ORM\Column(type="integer")
     */
    private $startTimeInterval;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime|null
     */
    private $firstTeamStartTime;

    /**
     * @ORM\Column(type="string", length=280)
     * @var string|null
     */
    private $joiningInfoURL;

    /**
     * @ORM\Column(type="string", length=280)
     * @var string|null
     */
    private $kitListURL;

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
    public function getMinWalkers(): ?int
    {
        return $this->minWalkers;
    }

    /**
     * @param int $minWalkers
     */
    public function setMinWalkers(int $minWalkers)
    {
        $this->minWalkers = $minWalkers;
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

    /**
     * @Groups({"hike"})
     * @return float|null
     */
    public function getFeePerWalker(): ?float
    {
        return $this->feePerWalker;
    }

    /**
     * @param float $feePerWalker
     */
    public function setFeePerWalker(float $feePerWalker): void
    {
        $this->feePerWalker = $feePerWalker;
    }

    /**
     * @return float|null
     */
    public function getMinAge(): ?float
    {
        return $this->minAge;
    }

    /**
     * @param float $minAge
     */
    public function setMinAge(float $minAge): void
    {
        $this->minAge = $minAge;
    }

    /**
     * @return float|null
     */
    public function getMaxAge(): ?float
    {
        return $this->maxAge;
    }

    /**
     * @param float $maxAge
     */
    public function setMaxAge(float $maxAge): void
    {
        $this->maxAge = $maxAge;
    }

    /**
     * @return int|null
     */
    public function getStartTimeInterval(): ?int
    {
        return $this->startTimeInterval;
    }

    /**
     * @param int $startTimeInterval
     */
    public function setStartTimeInterval(int $startTimeInterval): void
    {
        $this->startTimeInterval = $startTimeInterval;
    }

    /**
     * @return \DateTime|null
     */
    public function getFirstTeamStartTime(): ?\DateTime
    {
        return $this->firstTeamStartTime;
    }

    /**
     * @param \DateTime $firstTeamStartTime
     */
    public function setFirstTeamStartTime(\DateTime $firstTeamStartTime): void
    {
        $this->firstTeamStartTime = $firstTeamStartTime;
    }

    /**
     * @return string|null
     */
    public function getJoiningInfoURL(): ?string
    {
        return $this->joiningInfoURL;
    }

    /**
     * @param string|null $joiningInfoURL
     */
    public function setJoiningInfoURL(?string $joiningInfoURL): void
    {
        $this->joiningInfoURL = $joiningInfoURL;
    }

    /**
     * @return string|null
     */
    public function getKitListURL(): ?string
    {
        return $this->kitListURL;
    }

    /**
     * @param string|null $kitListURL
     */
    public function setKitListURL(?string $kitListURL): void
    {
        $this->kitListURL = $kitListURL;
    }
}
