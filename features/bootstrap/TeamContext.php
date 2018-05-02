<?php

namespace App\Context;

use App\Entity\Team;
use App\Factory\Entity\TeamFactory;
use App\Repository\EventRepository;
use App\Repository\HikeRepository;
use App\Repository\UserRepository;
use Behat\Behat\Context\Context;
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
     * @var HikeRepository
     */
    private $hikeRepository;

    /**
     * @param TeamFactory $teamFactory
     * @param HikeRepository $hikeRepository
     * @param EventRepository $eventRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        TeamFactory $teamFactory,
        HikeRepository $hikeRepository,
        EventRepository $eventRepository,
        UserRepository $userRepository
    ) {
        $this->teamFactory = $teamFactory;
        $this->eventRepository = $eventRepository;
        $this->userRepository = $userRepository;
        $this->hikeRepository = $hikeRepository;
    }

    /**
     * @Given /^that there is a Team called "([^"]*)" for the Hike "([^"]*)" on the Event "([^"]*)" registered by "([^"]*)"$/
     */
    public function thatThereIsATeamCalledForTheHikeOnTheEventRegisteredBy($teamName, $hikeName, $eventName, $userEmail)
    {
        $event = $this->eventRepository->findOneByName($eventName);
        Assert::assertNotNull($event, sprintf("No event found with name %s", $eventName));

        $hike = $this->hikeRepository->findOneByNameAndEvent($hikeName, $event);
        Assert::assertNotNull($hike, sprintf("No hike found with name %s for event %s", $hikeName, $event->getName()));

        $user = $this->userRepository->findOneByEmail($userEmail);
        Assert::assertNotNull($user, sprintf("No user found with email %s", $userEmail));

        $this->team = $this->teamFactory->createTeam($teamName, $hike, $user);
    }
}