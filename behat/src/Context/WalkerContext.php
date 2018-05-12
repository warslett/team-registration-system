<?php

namespace App\Behat\Context;

use App\Entity\Team;
use App\Behat\FixtureFactory\WalkerFactory;
use App\Behat\Service\FixtureStorageService;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;

class WalkerContext implements Context
{

    /**
     * @var WalkerFactory
     */
    private $walkerFactory;

    /**
     * @var FixtureStorageService
     */
    private $fixtureStorage;

    /**
     * @param WalkerFactory $walkerFactory
     * @param FixtureStorageService $fixtureStorage
     */
    public function __construct(
        WalkerFactory $walkerFactory,
        FixtureStorageService $fixtureStorage
    ) {
        $this->walkerFactory = $walkerFactory;
        $this->fixtureStorage = $fixtureStorage;
    }

    /**
     * @Given that :teamReference has the following Walkers:
     * @param $teamReference
     * @param TableNode $table
     */
    public function thatHasTheFollowingWalkers($teamReference, TableNode $table)
    {
        $team = $this->fixtureStorage->get(Team::class, $teamReference);
        foreach ($table->getColumnsHash() as $properties) {
            $this->walkerFactory->createTeam($team, $properties);
        }
    }

    /**
     * @Given that :teamReference has :numWalkers walkers
     */
    public function thatHasWalkers($teamReference, $numWalkers)
    {
        $team = $this->fixtureStorage->get(Team::class, $teamReference);
        for ($i = 0; $i < $numWalkers; $i++) {
            $this->walkerFactory->createTeam($team);
        }
    }
}
