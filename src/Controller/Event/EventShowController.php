<?php

namespace App\Controller\Event;

use App\Factory\ResponseFactory;
use App\Resolver\EventResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EventShowController
{

    /**
     * @var EventResolver
     */
    private $eventResolver;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @param EventResolver $eventResolver
     * @param ResponseFactory $responseFactory
     */
    public function __construct(EventResolver $eventResolver, ResponseFactory $responseFactory)
    {
        $this->eventResolver = $eventResolver;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $event = $this->eventResolver->resolveById($request->get('event_id'));
        return $this->responseFactory->createTemplateResponse('events/show.html.twig', ['event' => $event]);
    }
}
