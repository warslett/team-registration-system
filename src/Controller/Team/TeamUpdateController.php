<?php

namespace App\Controller\Team;

use App\Factory\ResponseFactory;
use App\FormManager\Team\TeamUpdateFormManager;
use App\Resolver\TeamResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class TeamUpdateController
{

    /**
     * @var TeamResolver
     */
    private $teamResolver;

    /**
     * @var TeamUpdateFormManager
     */
    private $teamUpdateFormManager;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    public function __construct(
        TeamResolver $teamResolver,
        TeamUpdateFormManager $teamUpdateFormManager,
        FlashBagInterface $flashBag,
        ResponseFactory $responseFactory
    ) {
        $this->teamResolver = $teamResolver;
        $this->teamUpdateFormManager = $teamUpdateFormManager;
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

        $form = $this->teamUpdateFormManager->createForm($team);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $team = $this->teamUpdateFormManager->processForm($form);

                $this->flashBag->add('success', sprintf(
                    "Team \"%s\" successfully updated for \"%s\"",
                    $team->getName(),
                    $team->getHike()->__toString()
                ));

                return $this->responseFactory->createRedirectResponse('team_show', [
                    'team_id' => $team->getId()
                ]);
            }

            $this->flashBag->add('danger', "There were some problems with the information you provided");
        }

        return $this->responseFactory->createTemplateResponse('teams/update.html.twig', [
            'team' => $team,
            'form' => $form->createView()
        ]);
    }
}
