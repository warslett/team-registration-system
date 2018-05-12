<?php

namespace App\Behat\FixtureFactory;

use App\Entity\Event;
use App\Entity\Hike;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Generator;

class HikeFactory
{
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
        $hike->setMaxWalkers($properties['maxWalkers'] ?? HikeFactory::DEFAULT_MAX_WALKERS);
        $hike->setEvent($event);
        $this->entityManager->persist($hike);
        $this->entityManager->flush();
        return $hike;
    }
}
