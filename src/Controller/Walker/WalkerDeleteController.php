<?php

namespace App\Controller\Walker;

use App\Factory\ResponseFactory;
use App\FormManager\Walker\WalkerDeleteFormManager;
use App\Resolver\WalkerResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class WalkerDeleteController
{

    /**
     * @var WalkerResolver
     */
    private $walkerResolver;

    /**
     * @var WalkerDeleteFormManager
     */
    private $walkerDeleteFormManager;

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
        WalkerDeleteFormManager $walkerDeleteFormManager,
        FlashBagInterface $flashBag,
        ResponseFactory $responseFactory
    ) {
        $this->walkerResolver = $walkerResolver;
        $this->walkerDeleteFormManager = $walkerDeleteFormManager;
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

        $form = $this->walkerDeleteFormManager->createForm($walker);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $walker = $this->walkerDeleteFormManager->processForm($form);

                $this->flashBag->add('success', sprintf(
                    "Walker \"%s\" successfully deleted",
                    $walker->getName()
                ));

                return $this->responseFactory->createRedirectResponse('team_show', [
                    'team_id' => $walker->getTeam()->getId()
                ]);
            }

            $this->flashBag->add('danger', "There were some problems with the information you provided");
        }

        return $this->responseFactory->createTemplateResponse('walkers/delete.html.twig', [
            'walker' => $walker,
            'form' => $form->createView()
        ]);
    }
}
