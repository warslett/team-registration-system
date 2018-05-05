<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

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
     */
    private $team;

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
}
