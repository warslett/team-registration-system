<?php

namespace App\Behat\Context;

use App\Entity\Hike;
use App\Entity\Team;
use App\Entity\User;
use App\Behat\FixtureFactory\TeamFactory;
use App\Behat\Service\FixtureStorageService;
use App\Resolver\UserGroupResolver;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;

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
     * @Given that :teamReference is a Team for :hikeReference registered by :userReference with the following properties:
     * @Given that :teamReference is a Team for :hikeReference registered by :userReference
     * @param string $teamReference
     * @param string $hikeReference
     * @param string $userReference
     * @param TableNode|null $table
     */
    public function thatIsATeamForRegisteredByWithTheFollowingProperties(
        string $teamReference,
        string $hikeReference,
        string $userReference,
        TableNode $table = null
    ) {
        $properties = is_null($table)?[]:$table->getRowsHash();
        $hike = $this->fixtureStorage->get(Hike::class, $hikeReference);
        $user = $this->fixtureStorage->get(User::class, $userReference);
        $team = $this->teamFactory->createTeam($hike, $user, $properties);
        $this->fixtureStorage->set(Team::class, $teamReference, $team);
    }

    /**
     * @Given /^that "([^"]*)" has (\d+) teams registered by "([^"]*)"$/
     * @param string $hikeReference
     * @param int $numTeams
     * @param $userReference
     */
    public function thatHasTeamsRegisteredBy(string $hikeReference, int $numTeams, string $userReference)
    {
        $user = $this->fixtureStorage->get(User::class, $userReference);
        $hike = $this->fixtureStorage->get(Hike::class, $hikeReference);
        for ($i = 0; $i < $numTeams; $i++) {
            $this->teamFactory->createTeam($hike, $user);
        }
    }
}
