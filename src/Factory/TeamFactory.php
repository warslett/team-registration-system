<?php


namespace App\Factory;

use App\Entity\Hike;
use App\Entity\Team;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class TeamFactory
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

    /**
     * @param string $teamName
     * @param Hike $hike
     * @param User $user
     * @return Team
     */
    public function createTeam(string $teamName, Hike $hike, User $user): Team
    {
        $team = new Team();
        $team->setName($teamName);
        $team->setHike($hike);
        $team->setUser($user);
        $this->entityManager->persist($team);
        $this->entityManager->flush();
        return $team;
    }
}