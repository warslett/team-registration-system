<?php

namespace App\Controller\Event;

use App\Factory\ResponseFactory;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EventListController
{

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @param ResponseFactory $responseFactory
     * @param EventRepository $eventRepository
     */
    public function __construct(ResponseFactory $responseFactory, EventRepository $eventRepository)
    {
        $this->responseFactory = $responseFactory;
        $this->eventRepository = $eventRepository;
    }

    public function __invoke(Request $request): Response
    {
        $events = $this->eventRepository->findAll();
        return $this->responseFactory->createTemplateResponse('events/list.html.twig', ['events' => $events]);
    }
}
