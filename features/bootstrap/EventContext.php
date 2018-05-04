<?php

namespace App\Context;

use App\Entity\Event;
use App\Factory\Fixture\EventFactory;
use App\Service\FixtureStorageService;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use function GuzzleHttp\Psr7\str;

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
     * @Given that :eventReference is an Event called :eventName taking place :dateString from now and taking registrations from :registrationOpenDateString from now until :registrationCloseDateString from now
     * @param string $eventReference
     * @param string $eventName
     * @param string $dateString
     * @param null|string $registrationOpenDateString
     * @param null|string $registrationCloseDate
     * @throws \Exception
     */
    public function thatIsAnEventCalledTakingPlaceAt(
        string $eventReference,
        string $eventName,
        string $dateString,
        ?string $registrationOpenDateString = null,
        ?string $registrationCloseDateString = null
    ) {
        $date = new \DateTime();
        $date->setTimestamp(strtotime($dateString));

        if (is_null($registrationOpenDateString)) {
            $registrationOpenDate = clone $date;
            $registrationOpenDate->sub(\DateInterval::createFromDateString("6 months"));
        } else {
            $registrationOpenDate = new \DateTime();
            $registrationOpenDate->setTimestamp(strtotime($registrationOpenDateString));
        }

        if (is_null($registrationCloseDateString)) {
            $registrationCloseDate = clone $date;
            $registrationCloseDate->sub(\DateInterval::createFromDateString("1 months"));
        } else {
            $registrationCloseDate = new \DateTime();
            $registrationCloseDate->setTimestamp(strtotime($registrationCloseDateString));
        }

        $event = $this->eventFactory->createEvent($eventName, $date, $registrationOpenDate, $registrationCloseDate);
        $this->fixtureStorage->set(Event::class, $eventReference, $event);
    }
}
