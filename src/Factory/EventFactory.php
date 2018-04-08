<?php


namespace App\Factory;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;

class EventFactory
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

    public function createEvent(string $eventName): Event
    {
        $event = new Event();
        $event->setName($eventName);
        $this->entityManager->persist($event);
        $this->entityManager->flush();
        return $event;
    }
}