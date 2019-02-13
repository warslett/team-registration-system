<?php

namespace App\Service;

use App\Entity\Hike;
use App\Entity\Team;
use App\Repository\TeamRepository;

class NextTeamStartTimeService
{

    /**
     * @var TeamRepository
     */
    private $teamRepository;

    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    /**
     * @param Hike $hike
     * @return \DateTime
     * @throws \Exception
     */
    public function getNextTeamStartTimeForHike(Hike $hike): \DateTime
    {
        $results = $this->teamRepository->createQueryBuilder('t')
            ->where('t.hike = :hike')
            ->andWhere('t.startTime IS NOT NULL')
            ->orderBy('t.startTime', 'DESC')
            ->setMaxResults(1)
            ->setParameter('hike', $hike)
            ->getQuery()
            ->execute()
        ;

        if (empty($results)) {
            return clone $hike->getFirstTeamStartTime();
        }

        /** @var Team $lastTeam */
        $lastTeam = $results[0];

        $dateInterval = new \DateInterval(sprintf("PT%dM", $hike->getStartTimeInterval()));

        $nextStartTime = clone $lastTeam->getStartTime();
        $nextStartTime->add($dateInterval);

        return $nextStartTime;
    }
}
