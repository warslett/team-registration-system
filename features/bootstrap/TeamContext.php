<?php


use App\Entity\Team;
use App\Factory\TeamFactory;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
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
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param TeamFactory $teamFactory
     * @param EventRepository $eventRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        TeamFactory $teamFactory,
        EventRepository $eventRepository,
        UserRepository $userRepository
    ) {
        $this->teamFactory = $teamFactory;
        $this->eventRepository = $eventRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Given /^that there is a Team called "([^"]*)" for the Event "([^"]*)" registered by "([^"]*)"$/
     * @param $teamName
     * @param $eventName
     * @param $userEmail
     */
    public function thatThereIsATeamCalledForTheEventCalled($teamName, $eventName, $userEmail)
    {
        $event = $this->eventRepository->findOneByName($eventName);
        Assert::assertNotNull($event, sprintf("No event found with name %s", $eventName));
        $user = $this->userRepository->findOneByEmail($userEmail);
        Assert::assertNotNull($user, sprintf("No user found with email %s", $userEmail));
        $this->team = $this->teamFactory->createTeam($teamName, $event, $user);
    }
}