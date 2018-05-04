<?php

namespace App\Context;

use App\Entity\Event;
use App\Entity\Hike;
use App\Factory\Fixture\HikeFactory;
use App\Service\FixtureStorageService;
use Behat\Behat\Context\Context;

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
     * @Given /^that "([^"]*)" is a Hike called "([^"]*)" for "([^"]*)"$/
     * @param string $hikeReference
     * @param string $hikeName
     * @param string $eventReference
     * @throws \Exception
     */
    public function thatIsAHikeCalledFor(string $hikeReference, string $hikeName, string $eventReference)
    {
        $event = $this->fixtureStorage->get(Event::class, $eventReference);
        $hike = $this->hikeFactory->createHike($hikeName, $event);
        $this->fixtureStorage->set(Hike::class, $hikeReference, $hike);
    }
}
