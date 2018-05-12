<?php

namespace App\Behat\FixtureFactory;

use App\Entity\Team;
use App\Entity\Walker;
use App\Service\WalkerReferenceCharacterService;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Generator;

class WalkerFactory
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Generator
     */
    private $faker;

    /**
     * @var WalkerReferenceCharacterService
     */
    private $walkerReferenceCharacterService;

    /**
     * UserFactory constructor.
     * @param EntityManagerInterface $entityManager
     * @param Generator $faker
     * @param WalkerReferenceCharacterService $walkerReferenceCharacterService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Generator $faker,
        WalkerReferenceCharacterService $walkerReferenceCharacterService
    ) {
        $this->entityManager = $entityManager;
        $this->faker = $faker;
        $this->walkerReferenceCharacterService = $walkerReferenceCharacterService;
    }

    /**
     * @param Team $team
     * @param array $properties
     * @return Walker
     */
    public function createTeam(Team $team, array $properties = []) : Walker {
        $walker = new Walker();
        $walker->setForeName($properties['foreName'] ?? $this->faker->firstName);
        $walker->setSurName($properties['surName'] ?? $this->faker->lastName);
        $walker->setReferenceCharacter(
            $this->walkerReferenceCharacterService->getNextAvailable($team)
        );
        $walker->setEmergencyContactNumber($properties['emergencyContactNumber'] ?? $this->faker->phoneNumber);
        $walker->setTeam($team);
        $this->entityManager->persist($walker);
        $this->entityManager->flush();
        return $walker;
    }
}
