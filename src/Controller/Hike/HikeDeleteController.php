<?php

namespace App\Controller\Hike;

use App\Factory\ResponseFactory;
use App\FormManager\Hike\HikeDeleteFormManager;
use App\Resolver\HikeResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class HikeDeleteController
{

    /**
     * @var HikeResolver
     */
    private $hikeResolver;

    /**
     * @var HikeDeleteFormManager
     */
    private $hikeDeleteFormManager;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    public function __construct(
        HikeResolver $hikeResolver,
        HikeDeleteFormManager $hikeDeleteFormManager,
        FlashBagInterface $flashBag,
        ResponseFactory $responseFactory
    ) {
        $this->hikeResolver = $hikeResolver;
        $this->hikeDeleteFormManager = $hikeDeleteFormManager;
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
        $hike = $this->hikeResolver->resolveById($request->get('hike_id'));

        $form = $this->hikeDeleteFormManager->createForm($hike);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $hike = $this->hikeDeleteFormManager->processForm($form);

                $this->flashBag->add('success', sprintf(
                    "Hike \"%s\" successfully deleted",
                    $hike->getName()
                ));

                return $this->responseFactory->createRedirectResponse('event_show', [
                    'event_id' => $hike->getEvent()->getId()
                ]);
            }

            $this->flashBag->add('danger', "There were some problems with the information you provided");
        }

        return $this->responseFactory->createTemplateResponse('hikes/delete.html.twig', [
            'hike' => $hike,
            'form' => $form->createView()
        ]);
    }
}
