<?php

namespace App\Controller\Hike;

use App\Factory\ResponseFactory;
use App\FormManager\Hike\HikeUpdateFormManager;
use App\Resolver\HikeResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class HikeUpdateController
{

    /**
     * @var HikeResolver
     */
    private $hikeResolver;

    /**
     * @var HikeUpdateFormManager
     */
    private $hikeUpdateFormManager;

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
        HikeUpdateFormManager $hikeUpdateFormManager,
        FlashBagInterface $flashBag,
        ResponseFactory $responseFactory
    )
    {
        $this->hikeResolver = $hikeResolver;
        $this->hikeUpdateFormManager = $hikeUpdateFormManager;
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

        $form = $this->hikeUpdateFormManager->createForm($hike);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $hike = $this->hikeUpdateFormManager->processForm($form);

                $this->flashBag->add('success', sprintf(
                    "Hike \"%s\" successfully updated for \"%s\"",
                    $hike->getName(),
                    $hike->getEvent()->getName()
                ));

                return $this->responseFactory->createRedirectResponse('hike_update', [
                    'hike_id' => $hike->getId()
                ]);
            }

            $this->flashBag->add('danger', "There were some problems with the information you provided");
        }

        return $this->responseFactory->createTemplateResponse('hikes/update.html.twig', [
            'hike' => $hike,
            'form' => $form->createView()
        ]);
    }
}