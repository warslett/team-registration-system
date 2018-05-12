<?php

namespace App\Behat\Context;

use App\Entity\Event;
use App\Entity\Hike;
use App\Behat\FixtureFactory\HikeFactory;
use App\Behat\Service\FixtureStorageService;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;

class HikeContext implements Context
{

    /**
     * @var HikeFactory
     */
    private $hikeFactory;

    /**
     * @var FixtureStorageService
     */
    private $fixtureStorage;

    public function __construct(
        HikeFactory $hikeFactory,
        FixtureStorageService $fixtureStorage
    ) {
        $this->hikeFactory = $hikeFactory;
        $this->fixtureStorage = $fixtureStorage;
    }

    /**
     * @Given that :hikeReference is a Hike for :eventReference with the following properties:
     * @Given that :hikeReference is a Hike for :eventReference
     */
    public function thatIsAHikeForWithTheFollowingProperties($hikeReference, $eventReference, TableNode $table = null)
    {
        $properties=is_null($table)?[]:$table->getRowsHash();
        $event = $this->fixtureStorage->get(Event::class, $eventReference);
        $hike = $this->hikeFactory->createHike($event, $properties);
        $this->fixtureStorage->set(Hike::class, $hikeReference, $hike);
    }
}
