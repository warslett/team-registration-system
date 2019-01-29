<?php

namespace App\Controller\Event;

use App\Factory\ResponseFactory;
use App\FormManager\Event\EventUpdateFormManager;
use App\Resolver\EventResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class EventUpdateController
{

    /**
     * @var EventResolver
     */
    private $eventResolver;

    /**
     * @var EventUpdateFormManager
     */
    private $eventUpdateFormManager;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    public function __construct(
        EventResolver $eventResolver,
        EventUpdateFormManager $eventUpdateFormManager,
        FlashBagInterface $flashBag,
        ResponseFactory $responseFactory
    ) {
        $this->eventResolver = $eventResolver;
        $this->eventUpdateFormManager = $eventUpdateFormManager;
        $this->flashBag = $flashBag;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig_Error
     */
    public function __invoke(Request $request): Response
    {
        $event = $this->eventResolver->resolveById($request->get('event_id'));

        $form = $this->eventUpdateFormManager->createForm($event);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $event = $this->eventUpdateFormManager->processForm($form);

                $this->flashBag->add('success', sprintf(
                    "Event \"%s\" successfully updated",
                    $event->getName()
                ));

                return $this->responseFactory->createRedirectResponse('event_update', [
                    'event_id' => $event->getId()
                ]);
            }

            $this->flashBag->add('danger', "There were some problems with the information you provided");
        }

        return $this->responseFactory->createTemplateResponse('events/update.html.twig', [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }
}
