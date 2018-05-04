<?php


namespace App\DataFixtures\Processor;

use App\Entity\Event;
use Fidry\AliceDataFixtures\ProcessorInterface;

class EventProcessor implements ProcessorInterface
{

    /**
     * Processes an object before it is persisted to DB.
     *
     * @param string $id Fixture ID
     * @param Event $object
     * @return void
     */
    public function preProcess(string $id, $object): void
    {
        if (($object instanceof Event) === false) {
            return;
        }
        $date = $object->getDate();
        $registrationOpensDate = clone $date;
        $registrationOpensDate->sub(\DateInterval::createFromDateString("6 months"));
        $registrationClosesDate = clone $date;
        $registrationClosesDate->sub(\DateInterval::createFromDateString("1 month"));
        $object->setRegistrationOpens($registrationOpensDate);
        $object->setRegistrationCloses($registrationClosesDate);
    }

    /**
     * Processes an object after it is persisted to DB.
     *
     * @param string $id Fixture ID
     * @param object $object
     * @return void
     */
    public function postProcess(string $id, $object): void
    {
    }
}
