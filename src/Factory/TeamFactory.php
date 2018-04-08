<?php


namespace App\Factory;


use App\Entity\Event;
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
     * @param Event $event
     * @param User $user
     * @return Team
     */
    public function createTeam(string $teamName, Event $event, User $user): Team
    {
        $team = new Team();
        $team->setName($teamName);
        $team->setEvent($event);
        $team->setUser($user);
        $this->entityManager->persist($team);
        $this->entityManager->flush();
        return $team;
    }
}