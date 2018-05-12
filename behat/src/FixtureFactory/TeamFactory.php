<?php

namespace App\Behat\FixtureFactory;

use App\Entity\Hike;
use App\Entity\Team;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Generator;

class TeamFactory
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
     * UserFactory constructor.
     * @param EntityManagerInterface $entityManager
     * @param Generator $faker
     */
    public function __construct(EntityManagerInterface $entityManager, Generator $faker)
    {
        $this->entityManager = $entityManager;
        $this->faker = $faker;
    }

    /**
     * @param Hike $hike
     * @param User $user
     * @param array $properties
     * @return Team
     */
    public function createTeam(Hike $hike, User $user, array $properties = []): Team
    {
        $team = new Team();
        $team->setName($properties['name'] ?? ucfirst(implode(' ', $this->faker->words)));
        $team->setHike($hike);
        $team->setUser($user);
        $this->entityManager->persist($team);
        $this->entityManager->flush();
        return $team;
    }
}
