<?php

namespace App\Context;

use App\Entity\Hike;
use App\Entity\Team;
use App\Entity\User;
use App\Factory\Fixture\TeamFactory;
use App\Service\FixtureStorageService;
use Behat\Behat\Context\Context;

class TeamContext implements Context
{

    /**
     * @var TeamFactory
     */
    private $teamFactory;

    /**
     * @var FixtureStorageService
     */
    private $fixtureStorage;

    /**
     * @param TeamFactory $teamFactory
     * @param FixtureStorageService $fixtureStorage
     */
    public function __construct(
        TeamFactory $teamFactory,
        FixtureStorageService $fixtureStorage
    ) {
        $this->teamFactory = $teamFactory;
        $this->fixtureStorage = $fixtureStorage;
    }

    /**
     * @Given /^that "([^"]*)" is a Team called "([^"]*)" for "([^"]*)" registered by "([^"]*)"$/
     * @param string $teamReference
     * @param string $teamName
     * @param string $hikeReference
     * @param string $userReference
     * @throws \Exception
     */
    public function thatIsATeamCalledForRegisteredBy(
        string $teamReference,
        string $teamName,
        string $hikeReference,
        string $userReference
    ) {
        $hike = $this->fixtureStorage->get(Hike::class, $hikeReference);
        $user = $this->fixtureStorage->get(User::class, $userReference);
        $team = $this->teamFactory->createTeam($teamName, $hike, $user);
        $this->fixtureStorage->set(Team::class, $teamReference, $team);
    }
}
