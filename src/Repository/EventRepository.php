<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Event::class));
    }

    /**
     * @param int $id
     * @param null $lockMode
     * @param null $lockVersion
     * @return Event|null
     */
    public function find($id, $lockMode = null, $lockVersion = null): ?Event
    {
        return parent::find($id, $lockMode, $lockVersion);
    }

    /**
     * @return array|Event[]
     */
    public function findAllOrderedByDate(): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.date', 'desc')
            ->getQuery()
            ->execute()
        ;
    }
}
