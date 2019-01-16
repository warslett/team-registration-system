<?php

namespace App\Controller\Walker;

use App\Factory\ResponseFactory;
use App\FormManager\Walker\WalkerUpdateFormManager;
use App\Resolver\WalkerResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class WalkerUpdateController
{

    /**
     * @var WalkerResolver
     */
    private $walkerResolver;

    /**
     * @var WalkerUpdateFormManager
     */
    private $walkerUpdateFormManager;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    public function __construct(
        WalkerResolver $walkerResolver,
        WalkerUpdateFormManager $walkerUpdateFormManager,
        FlashBagInterface $flashBag,
        ResponseFactory $responseFactory
    ) {
        $this->walkerResolver = $walkerResolver;
        $this->walkerUpdateFormManager = $walkerUpdateFormManager;
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
        $walker = $this->walkerResolver->resolveById($request->get('walker_id'));

        $form = $this->walkerUpdateFormManager->createForm($walker);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $walker = $this->walkerUpdateFormManager->processForm($form);

                $this->flashBag->add('success', sprintf(
                    "Walker \"%s\" successfully updated",
                    $walker->getName()
                ));

                return $this->responseFactory->createRedirectResponse('team_show', [
                    'team_id' => $walker->getTeam()->getId()
                ]);
            }

            $this->flashBag->add('danger', "There were some problems with the information you provided");
        }

        return $this->responseFactory->createTemplateResponse('walkers/update.html.twig', [
            'walker' => $walker,
            'form' => $form->createView()
        ]);
    }
}
