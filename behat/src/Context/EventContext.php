<?php

namespace App\Behat\Context;

use App\Entity\Event;
use App\Behat\FixtureFactory\EventFactory;
use App\Behat\Service\FixtureStorageService;
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
     * @Given /^that "([^"]*)" is an Event called "([^"]*)" taking place on "([^"]*)"$/
     * @Given /^that "([^"]*)" is an Event called "([^"]*)" taking place "([^"]*)" from now$/
     * @param string $eventReference
     * @param string $eventName
     * @param string $dateString
     * @throws \Exception
     */
    public function thatIsAnEventCalledTakingPlaceOn(
        string $eventReference,
        string $eventName,
        string $dateString
    ) {
        $date = new \DateTime();
        $date->setTimestamp(strtotime($dateString));

        $registrationOpenDate = clone $date;
        $registrationOpenDate->sub(\DateInterval::createFromDateString("6 months"));

        $registrationCloseDate = clone $date;
        $registrationCloseDate->sub(\DateInterval::createFromDateString("1 months"));

        $event = $this->eventFactory->createEvent($eventName, $date, $registrationOpenDate, $registrationCloseDate);
        $this->fixtureStorage->set(Event::class, $eventReference, $event);
    }

    /**
     * @Given that :eventReference is an Event called :eventName taking place on :dateString and taking registrations from :registrationOpenDateString until :registrationCloseDateString
     * @Given that :eventReference is an Event called :eventName taking place :dateString from now and taking registrations from :registrationOpenDateString from now until :registrationCloseDateString from now
     * @param string $eventReference
     * @param string $eventName
     * @param string $dateString
     * @param string $registrationOpenDateString
     * @param string $registrationCloseDateString
     * @throws \Exception
     */
    public function thatIsAnEventCalledTakingPlaceOnAndTakingRegistrationsFromUntil(
        string $eventReference,
        string $eventName,
        string $dateString,
        string $registrationOpenDateString,
        string $registrationCloseDateString
    ) {
        $date = new \DateTime();
        $date->setTimestamp(strtotime($dateString));

        $registrationOpenDate = new \DateTime();
        $registrationOpenDate->setTimestamp(strtotime($registrationOpenDateString));

        $registrationCloseDate = new \DateTime();
        $registrationCloseDate->setTimestamp(strtotime($registrationCloseDateString));

        $event = $this->eventFactory->createEvent($eventName, $date, $registrationOpenDate, $registrationCloseDate);
        $this->fixtureStorage->set(Event::class, $eventReference, $event);
    }
}
