<?php

namespace App\Repository;

use App\Entity\Hike;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class TeamRepository extends EntityRepository
{

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Team::class));
    }

    /**
     * @param string $teamName
     * @param Hike $hike
     * @return Team|null
     */
    public function findOneByNameAndHike(string $teamName, Hike $hike): ?Team
    {
        return $this->findOneBy(['name' => $teamName, 'hike' => $hike]);
    }
}
