<?php

namespace App\Controller\Team;

use App\Factory\ResponseFactory;
use App\FormManager\Team\TeamCreateFormManager;
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
    private $userCreateFormManager;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @param FlashBagInterface $flashBag
     * @param TeamCreateFormManager $userCreateFormManager
     * @param ResponseFactory $responseFactory
     */
    public function __construct(
        FlashBagInterface $flashBag,
        TeamCreateFormManager $userCreateFormManager,
        ResponseFactory $responseFactory
    ) {
        $this->flashBag = $flashBag;
        $this->userCreateFormManager = $userCreateFormManager;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error
     */
    public function __invoke(Request $request)
    {
        $form = $this->userCreateFormManager->createForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $team = $this->userCreateFormManager->processForm($form);

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

        return $this->responseFactory->createTemplateResponse('team/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
