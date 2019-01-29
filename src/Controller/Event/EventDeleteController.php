<?php

namespace App\Controller\Event;

use App\Factory\ResponseFactory;
use App\FormManager\Event\EventDeleteFormManager;
use App\Resolver\EventResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class EventDeleteController
{

    /**
     * @var EventResolver
     */
    private $eventResolver;

    /**
     * @var EventDeleteFormManager
     */
    private $eventDeleteFormManager;

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
        EventDeleteFormManager $eventDeleteFormManager,
        FlashBagInterface $flashBag,
        ResponseFactory $responseFactory
    ) {
        $this->eventResolver = $eventResolver;
        $this->eventDeleteFormManager = $eventDeleteFormManager;
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

        $form = $this->eventDeleteFormManager->createForm($event);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $event = $this->eventDeleteFormManager->processForm($form);

                $this->flashBag->add('success', sprintf(
                    "Event \"%s\" successfully deleted",
                    $event->getName()
                ));

                return $this->responseFactory->createRedirectResponse('event_list');
            }

            $this->flashBag->add('danger', "There were some problems with the information you provided");
        }

        return $this->responseFactory->createTemplateResponse('events/delete.html.twig', [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }
}
