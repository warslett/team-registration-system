<?php

namespace App\Behat\Context;

use App\Entity\Event;
use App\Behat\FixtureFactory\EventFactory;
use App\Behat\Service\FixtureStorageService;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;

class EventContext implements Context
{

    /**
     * @var EventFactory
     */
    private $eventFactory;

    /**
     * @var FixtureStorageService
     */
    private $fixtureStorage;

    /**
     * EventContext constructor.
     * @param EventFactory $eventFactory
     * @param FixtureStorageService $fixtureStorage
     */
    public function __construct(EventFactory $eventFactory, FixtureStorageService $fixtureStorage)
    {
        $this->eventFactory = $eventFactory;
        $this->fixtureStorage = $fixtureStorage;
    }

    /**
     * @Given that :eventReference is an Event with the following properties:
     * @Given that :eventReference is an Event
     * @param string $eventReference
     * @param TableNode|null $table
     * @throws \Exception
     */
    public function thatIsAnEventWithTheFollowingProperties(string $eventReference, TableNode $table = null)
    {
        $properties=is_null($table)?[]:$table->getRowsHash();
        $event = $this->eventFactory->createEvent($properties);
        $this->fixtureStorage->set(Event::class, $eventReference, $event);
    }
}
