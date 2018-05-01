<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Hike;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class HikeRepository extends EntityRepository
{

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Hike::class));
    }

    /**
     * @param string $hikeName
     * @param Event $event
     * @return Hike|null
     */
    public function findOneByNameAndEvent(string $hikeName, Event $event): ?Hike
    {
        return $this->findOneBy(['name' => $hikeName, 'event' => $event]);
    }
}
