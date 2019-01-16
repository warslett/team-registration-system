<?php

namespace App\Controller\Team;

use App\Factory\ResponseFactory;
use App\FormManager\Team\TeamDropFormManager;
use App\FormManager\Team\TeamReinstateFormManager;
use App\Resolver\TeamResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class TeamReinstateController
{

    /**
     * @var TeamResolver
     */
    private $teamResolver;

    /**
     * @var TeamReinstateFormManager
     */
    private $teamReinstateFormManager;

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
     * @param TeamReinstateFormManager $teamReinstateFormManager
     * @param FlashBagInterface $flashBag
     * @param ResponseFactory $responseFactory
     */
    public function __construct(
        TeamResolver $teamResolver,
        TeamReinstateFormManager $teamReinstateFormManager,
        FlashBagInterface $flashBag,
        ResponseFactory $responseFactory
    ) {
        $this->teamResolver = $teamResolver;
        $this->teamReinstateFormManager = $teamReinstateFormManager;
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
        $team = $this->teamResolver->resolveById($request->get('team_id'));

        $form = $this->teamReinstateFormManager->createForm($team);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $team = $this->teamReinstateFormManager->processForm($form);

                $this->flashBag->add('success', sprintf(
                    "Team \"%s\" is now reinstate for \"%s\"",
                    $team->getName(),
                    $team->getHike()->__toString()
                ));

                return $this->responseFactory->createRedirectResponse('team_show', [
                    'team_id' => $team->getId()
                ]);
            }

            $this->flashBag->add('danger', "There were some problems with the information you provided");
        }

        return $this->responseFactory->createTemplateResponse('teams/reinstate.html.twig', [
            'team' => $team,
            'form' => $form->createView()
        ]);
    }
}
