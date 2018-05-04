<?php

namespace App\Context;

use App\Entity\Event;
use App\Factory\Fixture\EventFactory;
use App\Service\FixtureStorageService;
use Behat\Behat\Context\Context;

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
     * @Given /^that "([^"]*)" is an Event called "([^"]*)" taking place at "([^"]*)"$/
     * @Given /^that "([^"]*)" is an Event called "([^"]*)" taking place on "([^"]*)"$/
     * @Given /^that "([^"]*)" is an Event called "([^"]*)" taking place "([^"]*)" from now$/
     * @param string $eventReference
     * @param string $eventName
     * @param string $dateString
     * @throws \Exception
     */
    public function thatIsAnEventCalledTakingPlaceAt(string $eventReference, string $eventName, string $dateString)
    {
        $date = new \DateTime();
        $date->setTimestamp(strtotime($dateString));
        $event = $this->eventFactory->createEvent($eventName, $date);
        $this->fixtureStorage->set(Event::class, $eventReference, $event);
    }
}
