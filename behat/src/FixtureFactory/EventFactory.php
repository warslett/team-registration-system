<?php

namespace App\Behat\FixtureFactory;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;

class EventFactory
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $eventName
     * @param \DateTime $dateTime
     * @param \DateTime $registrationOpenDate
     * @param \DateTime $registrationCloseDate
     * @return Event
     */
    public function createEvent(
        string $eventName,
        \DateTime $dateTime,
        \DateTime $registrationOpenDate,
        \DateTime $registrationCloseDate
    ): Event {
        $event = new Event();
        $event->setName($eventName);
        $event->setDate($dateTime);
        $event->setRegistrationOpens($registrationOpenDate);
        $event->setRegistrationCloses($registrationCloseDate);
        $this->entityManager->persist($event);
        $this->entityManager->flush();
        return $event;
    }
}
