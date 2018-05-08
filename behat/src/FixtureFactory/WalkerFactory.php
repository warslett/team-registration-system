<?php

namespace App\Behat\FixtureFactory;

use App\Entity\Team;
use App\Entity\Walker;
use Doctrine\ORM\EntityManagerInterface;

class WalkerFactory
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * UserFactory constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $foreName
     * @param string $surName
     * @param string $referenceCharacter
     * @param string $emergencyContactNumber
     * @param Team $team
     * @return Walker
     */
    public function createTeam(
        string $foreName,
        string $surName,
        string $referenceCharacter,
        string $emergencyContactNumber,
        Team $team
    ): Walker {
        $walker = new Walker();
        $walker->setForeName($foreName);
        $walker->setSurName($surName);
        $walker->setReferenceCharacter($referenceCharacter);
        $walker->setEmergencyContactNumber($emergencyContactNumber);
        $walker->setTeam($team);
        $this->entityManager->persist($walker);
        $this->entityManager->flush();
        return $walker;
    }
}
