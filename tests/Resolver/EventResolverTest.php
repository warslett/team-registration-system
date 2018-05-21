<?php

namespace App\Tests\Resolver;

use App\Entity\Event;
use App\Repository\EventRepository;
use App\Resolver\EventResolver;
use App\Tests\TestCase;
use Mockery as m;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventResolverTest extends TestCase
{

    public function testResolveById_callsEventRepository()
    {
        $eventRepository = $this->mockEventRepository($this->mockEvent());
        $eventResolver = new EventResolver($eventRepository);
        $id = 1;

        $eventResolver->resolveById($id);

        $eventRepository->shouldHaveReceived('find')->with($id);
    }

    public function testResolveById_EventDoesNotExist_throwsException()
    {
        $eventRepository = $this->mockEventRepository(null);
        $eventResolver = new EventResolver($eventRepository);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage("No event found with the id 1");

        $eventResolver->resolveById(1);
    }

    public function testResolveById_EventExists_returnsEvent()
    {
        $event = $this->mockEvent();
        $eventRepository = $this->mockEventRepository($event);
        $eventResolver = new EventResolver($eventRepository);

        $actual = $eventResolver->resolveById(1);

        $this->assertEquals($event, $actual);
    }

    /**
     * @param Event|null $event
     * @return EventRepository|m\Mock
     */
    private function mockEventRepository(?Event $event): EventRepository
    {
        $eventRepository = m::mock(EventRepository::class);
        $eventRepository->shouldReceive('find')->andReturn($event);
        return $eventRepository;
    }

    /**
     * @return Event|m\Mock
     */
    private function mockEvent(): Event
    {
        $event = m::mock(Event::class);
        return $event;
    }
}
