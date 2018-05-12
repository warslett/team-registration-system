<?php

namespace App\Behat\FixtureFactory;

use App\Behat\Helper\DateHelper;
use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Generator;

class EventFactory
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
     * @var DateHelper
     */
    private $dateHelper;

    /**
     * @param EntityManagerInterface $entityManager
     * @param Generator $faker
     * @param DateHelper $dateHelper
     */
    public function __construct(EntityManagerInterface $entityManager, Generator $faker, DateHelper $dateHelper)
    {
        $this->entityManager = $entityManager;
        $this->faker = $faker;
        $this->dateHelper = $dateHelper;
    }

    /**
     * @param array $properties
     * @return Event
     */
    public function createEvent(array $properties = []): Event
    {
        $event = new Event();
        $event->setName($properties['name'] ?? ucfirst(implode(' ', $this->faker->words)));

        $event->setDate(
            is_null($properties['date'])
                ? (is_null($properties['dateIn'])
                    ? new \DateTime("+3 months")
                    : $this->dateHelper->dateIn($properties['dateIn']))
                : $this->dateHelper->date($properties['date'])
        );

        $event->setRegistrationOpens(
            is_null($properties['registrationOpens'])
            ? (is_null($properties['registrationOpensIn'])
                ? $this->dateHelper->dateSub($event->getDate(), "6 months")
                : $this->dateHelper->dateIn($properties['registrationOpensIn']))
            : $this->dateHelper->date($properties['registrationOpens'])
        );

        $event->setRegistrationCloses(
            is_null($properties['registrationCloses'])
                ? (is_null($properties['registrationClosesIn'])
                    ? $this->dateHelper->dateSub($event->getDate(), "1 month")
                    : $this->dateHelper->dateIn($properties['registrationClosesIn']))
                : $this->dateHelper->date($properties['registrationCloses'])
        );

        $this->entityManager->persist($event);
        $this->entityManager->flush();

        return $event;
    }
}
