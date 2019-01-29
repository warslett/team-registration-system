<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WalkerRepository")
 * @UniqueEntity(fields={"referenceCharacter","team"})
 */
class Walker
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1, options={"fixed" = true})
     * @var string
     */
    private $referenceCharacter;

    /**
     * @ORM\Column(type="string", length=100)
     * @var string
     */
    private $foreName;

    /**
     * @ORM\Column(type="string", length=100)
     * @var string
     */
    private $surName;

    /**
     * @ORM\Column(type="string", length=100)
     * @var string
     */
    private $emergencyContactNumber;

    /**
     * @var Team
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="walkers")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $team;

    /**
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    private $dateOfBirth;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getForeName(): ?string
    {
        return $this->foreName;
    }

    /**
     * @param string $foreName
     */
    public function setForeName(string $foreName)
    {
        $this->foreName = $foreName;
    }

    /**
     * @return string|null
     */
    public function getSurName(): ?string
    {
        return $this->surName;
    }

    /**
     * @param string $surName
     */
    public function setSurName(string $surName)
    {
        $this->surName = $surName;
    }

    /**
     * @return string|null
     */
    public function getEmergencyContactNumber(): ?string
    {
        return $this->emergencyContactNumber;
    }

    /**
     * @param string $emergencyContactNumber
     */
    public function setEmergencyContactNumber(string $emergencyContactNumber)
    {
        $this->emergencyContactNumber = $emergencyContactNumber;
    }

    /**
     * @return Team|null
     */
    public function getTeam(): ?Team
    {
        return $this->team;
    }

    /**
     * @param Team $team
     */
    public function setTeam(Team $team)
    {
        $this->team = $team;
    }

    /**
     * @return string|null
     */
    public function getReferenceCharacter(): ?string
    {
        return $this->referenceCharacter;
    }

    /**
     * @param string $referenceCharacter
     */
    public function setReferenceCharacter(string $referenceCharacter)
    {
        $this->referenceCharacter = $referenceCharacter;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->team->getId() . $this->referenceCharacter;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->foreName . ' ' . $this->surName;
    }
    
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return \DateTime|null
     */
    public function getDateOfBirth(): ?\DateTime
    {
        return $this->dateOfBirth;
    }

    /**
     * @param \DateTime $dateOfBirth
     */
    public function setDateOfBirth(\DateTime $dateOfBirth): void
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * @return null|float
     */
    public function getAgeOnHike(): ?float
    {
        if (is_null($this->dateOfBirth)) {
            return null;
        }

        $interval = $this->dateOfBirth->diff($this->team->getHike()->getEvent()->getDate());
        return floatval($interval->y) + floatval($interval->m)/12;
    }

    /**
     * @return null|string
     */
    public function getReadableAgeOnHike(): ?string
    {
        if (is_null($this->dateOfBirth)) {
            return null;
        }

        $interval = $this->dateOfBirth->diff($this->team->getHike()->getEvent()->getDate());
        return sprintf("%d years %d months", $interval->y, $interval->m);
    }

    /**
     * @Constraints\Callback
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        if ($this->getAgeOnHike() > $this->team->getHike()->getMaxAge()) {
            $context->buildViolation('This walker is too old to enter this event')
                ->atPath('dateOfBirth')
                ->addViolation();
        }

        if ($this->getAgeOnHike() < $this->team->getHike()->getMinAge()) {
            $context->buildViolation('This walker is too young to enter this event')
                ->atPath('dateOfBirth')
                ->addViolation();
        }
    }
}
