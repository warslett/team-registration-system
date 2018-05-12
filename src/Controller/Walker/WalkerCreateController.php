<?php

namespace App\Controller\Walker;

use App\Factory\ResponseFactory;
use App\FormManager\Walker\WalkerCreateFormManager;
use App\Resolver\TeamResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class WalkerCreateController
{

    /**
     * @var TeamResolver
     */
    private $teamResolver;

    /**
     * @var WalkerCreateFormManager
     */
    private $walkerCreateFormManager;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @param TeamResolver $teamResolver
     * @param WalkerCreateFormManager $walkerCreateFormManager
     * @param FlashBagInterface $flashBag
     * @param ResponseFactory $responseFactory
     */
    public function __construct(
        TeamResolver $teamResolver,
        WalkerCreateFormManager $walkerCreateFormManager,
        FlashBagInterface $flashBag,
        ResponseFactory $responseFactory
    ) {
        $this->teamResolver = $teamResolver;
        $this->walkerCreateFormManager = $walkerCreateFormManager;
        $this->flashBag = $flashBag;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $team = $this->teamResolver->resolveById($request->get('team_id'));

        if ($team->hasMaxWalkers()) {
            $this->flashBag->add('danger', "This team already has the maximum number of allowed walkers");

            return $this->responseFactory->createRedirectResponse('team_show', [
                'team_id' => $team->getId()
            ]);
        }

        $form = $this->walkerCreateFormManager->createForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $walker = $this->walkerCreateFormManager->processForm($team, $form);

                $this->flashBag->add('success', sprintf(
                    "Walker \"%s\" successfully added to team \"%s\"",
                    $walker->getName(),
                    $team->getName()
                ));

                return $this->responseFactory->createRedirectResponse('team_show', [
                    'team_id' => $team->getId()
                ]);
            }

            $this->flashBag->add('danger', "There were some problems with the information you provided");
        }

        return $this->responseFactory->createTemplateResponse('teams/walkers/create.html.twig', [
            'team' => $team,
            'form' => $form->createView()
        ]);
    }
}
