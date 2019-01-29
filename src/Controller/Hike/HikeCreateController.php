<?php

namespace App\Controller\Hike;

use App\Factory\ResponseFactory;
use App\FormManager\Hike\HikeCreateFormManager;
use App\Resolver\EventResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class HikeCreateController
{

    /**
     * @var EventResolver
     */
    private $eventResolver;

    /**
     * @var HikeCreateFormManager
     */
    private $hikeCreateFormManager;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @param EventResolver $eventResolver
     * @param HikeCreateFormManager $hikeCreateFormManager
     * @param FlashBagInterface $flashBag
     * @param ResponseFactory $responseFactory
     */
    public function __construct(
        EventResolver $eventResolver,
        HikeCreateFormManager $hikeCreateFormManager,
        FlashBagInterface $flashBag,
        ResponseFactory $responseFactory
    ) {
        $this->eventResolver = $eventResolver;
        $this->hikeCreateFormManager = $hikeCreateFormManager;
        $this->flashBag = $flashBag;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $event = $this->eventResolver->resolveById($request->get('event_id'));
        $form = $this->hikeCreateFormManager->createForm($event);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $hike = $this->hikeCreateFormManager->processForm($form);

                $this->flashBag->add('success', sprintf(
                    "Hike \"%s\" successfully added to event \"%s\"",
                    $hike->getName(),
                    $event->getName()
                ));

                return $this->responseFactory->createRedirectResponse('event_show', [
                    'event_id' => $event->getId()
                ]);
            }

            $this->flashBag->add('danger', "There were some problems with the information you provided");
        }

        return $this->responseFactory->createTemplateResponse('hikes/create.html.twig', [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }
}
