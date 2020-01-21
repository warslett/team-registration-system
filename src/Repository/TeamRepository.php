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
     * @param int $id
     * @param null $lockMode
     * @param null $lockVersion
     * @return Team|null
     */
    public function find($id, $lockMode = null, $lockVersion = null): ?Team
    {
        return parent::find($id, $lockMode, $lockVersion);
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

    /**
     * @param Hike $hike
     * @param string $teamNumber
     * @return Team|null
     */
    public function findOneByTeamForHikeWithTeamNumber(Hike $hike, string $teamNumber): ?Team
    {
        return $this->findOneBy(['hike' => $hike, 'teamNumber' => $teamNumber]);
    }

    /**
     * @param Hike $hike
     * @param \DateTime $startTime
     * @return Team|null
     */
    public function findOneTeamForHikeWithStartTime(Hike $hike, \DateTime $startTime): ?Team
    {
        return $this->findOneBy(['hike' => $hike, 'startTime' => $startTime]);
    }
}
