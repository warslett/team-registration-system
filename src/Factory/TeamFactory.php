<?php


namespace App\Factory;


use App\Entity\Event;
use App\Entity\Team;
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
     * @return Team
     */
    public function createTeam(string $teamName, Event $event): Team
    {
        $team = new Team();
        $team->setName($teamName);
        $team->setEvent($event);
        $this->entityManager->persist($team);
        $this->entityManager->flush();
        return $team;
    }
}