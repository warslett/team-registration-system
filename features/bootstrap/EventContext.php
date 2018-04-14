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
     * @Given that there is an Event called :eventName taking place :interval from now
     * @Given that there is an Event called :eventName taking place at :interval
     * @param string $eventName
     * @param string $interval
     */
    public function thatThereIsAnEventCalledTakingPlaceFromNow(string $eventName, string $interval)
    {
        $date = new \DateTime();
        $date->setTimestamp(strtotime($interval));
        $this->event = $this->eventFactory->createEvent($eventName, $date);
    }
}
