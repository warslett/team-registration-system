<?php

namespace App\Controller\Team;

use App\Factory\ResponseFactory;
use App\FormManager\Team\TeamCreateFormManager;
use App\Repository\HikeRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class TeamCreateController
{

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var TeamCreateFormManager
     */
    private $teamCreateFormManager;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var HikeRepository
     */
    private $hikeRepository;

    /**
     * @param FlashBagInterface $flashBag
     * @param TeamCreateFormManager $teamCreateFormManager
     * @param ResponseFactory $responseFactory
     * @param HikeRepository $hikeRepository
     */
    public function __construct(
        FlashBagInterface $flashBag,
        TeamCreateFormManager $teamCreateFormManager,
        ResponseFactory $responseFactory,
        HikeRepository $hikeRepository
    ) {
        $this->flashBag = $flashBag;
        $this->teamCreateFormManager = $teamCreateFormManager;
        $this->responseFactory = $responseFactory;
        $this->hikeRepository = $hikeRepository;
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error
     */
    public function __invoke(Request $request)
    {
        if (!$this->hikeRepository->hasHikesOpenToRegistration()) {
            $this->flashBag->add('warning', "There are currently no events open for registration");

            return $this->responseFactory->createRedirectResponse('team_list');
        }

        $form = $this->teamCreateFormManager->createForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $team = $this->teamCreateFormManager->processForm($form);

                $this->flashBag->add('success', sprintf(
                    "Team \"%s\" successfully created for \"%s\"",
                    $team->getName(),
                    $team->getHike()->__toString()
                ));

                return $this->responseFactory->createRedirectResponse('team_show', [
                    'team_id' => $team->getId()
                ]);
            }

            $this->flashBag->add('danger', "There were some problems with the information you provided");
        }

        return $this->responseFactory->createTemplateResponse('teams/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
