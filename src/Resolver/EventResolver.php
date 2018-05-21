<?php

namespace App\Resolver;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventResolver
{

    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @param EventRepository $eventRepository
     */
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param int $eventId
     * @return Event
     */
    public function resolveById(int $eventId): Event
    {
        $event = $this->eventRepository->find($eventId);
        if (is_null($event)) {
            throw new NotFoundHttpException(sprintf("No event found with the id %d", $eventId));
        }
        return $event;
    }
}
