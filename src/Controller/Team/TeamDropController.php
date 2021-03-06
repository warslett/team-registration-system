<?php

namespace App\Controller\Team;

use App\Factory\ResponseFactory;
use App\FormManager\Team\TeamDropFormManager;
use App\Resolver\TeamResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class TeamDropController
{

    /**
     * @var TeamResolver
     */
    private $teamResolver;

    /**
     * @var TeamDropFormManager
     */
    private $teamDropFormManager;
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
     * @param TeamDropFormManager $teamDropFormManager
     * @param FlashBagInterface $flashBag
     * @param ResponseFactory $responseFactory
     */
    public function __construct(
        TeamResolver $teamResolver,
        TeamDropFormManager $teamDropFormManager,
        FlashBagInterface $flashBag,
        ResponseFactory $responseFactory
    ) {
        $this->teamResolver = $teamResolver;
        $this->teamDropFormManager = $teamDropFormManager;
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

        $form = $this->teamDropFormManager->createForm($team);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $team = $this->teamDropFormManager->processForm($form);

                $this->flashBag->add('success', sprintf(
                    "Team \"%s\" is now marked as dropped from \"%s\"",
                    $team->getName(),
                    $team->getHike()->__toString()
                ));

                return $this->responseFactory->createRedirectResponse('team_show', [
                    'team_id' => $team->getId()
                ]);
            }

            $this->flashBag->add('danger', "There were some problems with the information you provided");
        }

        return $this->responseFactory->createTemplateResponse('teams/drop.html.twig', [
            'team' => $team,
            'form' => $form->createView()
        ]);
    }
}
