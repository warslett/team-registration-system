<?php


namespace App\Factory;

use App\Entity\Event;
use App\Entity\Hike;
use Doctrine\ORM\EntityManagerInterface;

class HikeFactory
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

    public function createHike(string $hikeName, Event $event): Hike
    {
        $hike = new Hike();
        $hike->setName($hikeName);
        $hike->setEvent($event);
        $this->entityManager->persist($hike);
        $this->entityManager->flush();
        return $hike;
    }
}
