<?php

use App\Entity\Hike;
use App\Factory\HikeFactory;
use App\Repository\EventRepository;
use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;

class HikeContext implements Context
{

    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @var HikeFactory
     */
    private $hikeFactory;

    /**
     * @var Hike
     */
    private $hike;

    public function __construct(EventRepository $eventRepository, HikeFactory $hikeFactory)
    {
        $this->eventRepository = $eventRepository;
        $this->hikeFactory = $hikeFactory;
    }

    /**
     * @Given /^that there is a Hike called "([^"]*)" for the Event "([^"]*)"$/
     * @param string $hikeName
     * @param string $eventName
     */
    public function thatThereIsAHikeCalledForTheEvent(string $hikeName, string $eventName)
    {
        $event = $this->eventRepository->findOneByName($eventName);
        Assert::assertNotNull($event, sprintf("No event found with name %s", $eventName));
        $this->hike = $this->hikeFactory->createHike($hikeName, $event);
    }
}