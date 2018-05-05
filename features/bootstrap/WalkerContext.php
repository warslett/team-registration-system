<?php

namespace App\Context;

use App\Entity\Team;
use App\Factory\Fixture\WalkerFactory;
use App\Service\FixtureStorageService;
use Behat\Behat\Context\Context;

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
     * @Given /^that "([^"]*)" has the following Walkers:$/
     */
    public function thatHasTheFollowingWalkers($teamReference, \Behat\Gherkin\Node\TableNode $table)
    {
        $team = $this->fixtureStorage->get(Team::class, $teamReference);
        foreach ($table->getColumnsHash() as $row) {
            $this->walkerFactory->createTeam(
                $row['Forename'],
                $row['Surname'],
                $row['Reference Character'],
                $row['Emergency Contact Number'],
                $team
            );
        }
    }
}