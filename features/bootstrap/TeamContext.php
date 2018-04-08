<?php


use App\Entity\Team;
use App\Factory\TeamFactory;
use App\Repository\EventRepository;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Assert;

class TeamContext implements Context
{

    /**
     * @var TeamFactory
     */
    private $teamFactory;

    /**
     * @var null|Team
     */
    private $team = null;
    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @param TeamFactory $teamFactory
     * @param EventRepository $eventRepository
     * @internal param EntityManagerInterface $entityManager
     */
    public function __construct(TeamFactory $teamFactory, EventRepository $eventRepository)
    {
        $this->teamFactory = $teamFactory;
        $this->eventRepository = $eventRepository;
    }

    /**
     * @Given /^that there is a Team called "([^"]*)" for the Event called "([^"]*)"$/
     */
    public function thatThereIsATeamCalledForTheEventCalled($teamName, $eventName)
    {
        $event = $this->eventRepository->findOneByName($eventName);
        Assert::assertNotNull($event, sprintf("No event found with name %s", $eventName));
        $this->team = $this->teamFactory->createTeam($teamName, $event);
    }
}