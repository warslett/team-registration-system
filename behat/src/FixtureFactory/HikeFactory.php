<?php

namespace App\Behat\FixtureFactory;

use App\Entity\Event;
use App\Entity\Hike;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Generator;

class HikeFactory
{

    const DEFAULT_MIN_WALKERS = 3;
    const DEFAULT_MAX_WALKERS = 4;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Generator
     */
    private $faker;

    /**
     * @param EntityManagerInterface $entityManager
     * @param Generator $faker
     */
    public function __construct(EntityManagerInterface $entityManager, Generator $faker)
    {
        $this->entityManager = $entityManager;
        $this->faker = $faker;
    }

    /**
     * @param Event $event
     * @param array $properties
     * @return Hike
     */
    public function createHike(Event $event, array $properties = []): Hike
    {
        $hike = new Hike();
        $hike->setName($properties['name'] ?? ucfirst(implode(' ', $this->faker->words)));
        $hike->setMinWalkers($properties['minWalkers'] ?? HikeFactory::DEFAULT_MIN_WALKERS);
        $hike->setMaxWalkers($properties['maxWalkers'] ?? HikeFactory::DEFAULT_MAX_WALKERS);
        $hike->setFeePerWalker(
            $properties['feePerWalker'] ?? $this->faker->randomFloat(2, 10, $max = 16)
        );
        $hike->setMinAge($properties['minAge'] ?? $this->faker->numberBetween(10, 14));
        $hike->setMaxAge($properties['maxAge'] ?? $this->faker->numberBetween(14, 18));
        $hike->setStartTimeInterval($properties['startTimeInterval'] ?? $this->faker->numberBetween(2, 20));
        $hike->setFirstTeamStartTime($properties['firstTeamStartTime'] ?? $event->getDate());
        $hike->setJoiningInfoURL($properties['joiningInfoURL'] ?? $this->faker->url);
        $hike->setKitListURL($properties['kitListURL'] ?? $this->faker->url);
        $hike->setEvent($event);
        $this->entityManager->persist($hike);
        $this->entityManager->flush();
        return $hike;
    }
}
