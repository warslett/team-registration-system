<?php

use App\Factory\EventFactory;
use Behat\Behat\Context\Context;

class EventContext implements Context
{
    /**
     * @var Event
     */
    private $event;

    /**
     * @var EventFactory
     */
    private $eventFactory;

    public function __construct(EventFactory $eventFactory)
    {

        $this->eventFactory = $eventFactory;
    }

    /**
     * @Given /^that there is an Event called "([^"]*)"$/
     */
    public function thatThereIsAnEventCalled($eventName)
    {
        $this->event = $this->eventFactory->createEvent($eventName);
    }
}
