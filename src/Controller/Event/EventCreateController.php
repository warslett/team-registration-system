<?php

namespace App\Controller\Event;

use App\Factory\ResponseFactory;
use App\FormManager\Event\EventCreateFormManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class EventCreateController
{

    /**
     * @var EventCreateFormManager
     */
    private $eventCreateFormManager;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @param EventCreateFormManager $eventCreateFormManager
     * @param ResponseFactory $responseFactory
     * @param FlashBagInterface $flashBag
     */
    public function __construct(
        EventCreateFormManager $eventCreateFormManager,
        ResponseFactory $responseFactory,
        FlashBagInterface $flashBag
    ) {
        $this->eventCreateFormManager = $eventCreateFormManager;
        $this->responseFactory = $responseFactory;
        $this->flashBag = $flashBag;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->eventCreateFormManager->createForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $event = $this->eventCreateFormManager->processForm($form);

                $this->flashBag->add('success', sprintf(
                    "Event \"%s\" successfully created",
                    $event->getName()
                ));

                return $this->responseFactory->createRedirectResponse('event_show', [
                    'event_id' => $event->getId()
                ]);
            }

            $this->flashBag->add('danger', "There were some problems with the information you provided");
        }

        return $this->responseFactory->createTemplateResponse('events/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
