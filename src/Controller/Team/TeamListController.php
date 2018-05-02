<?php

namespace App\Controller\Team;

use App\Factory\ResponseFactory;
use App\Service\CurrentUserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamListController
{

    /**
     * @var CurrentUserService
     */
    private $currentUserService;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @param CurrentUserService $currentUserService
     * @param ResponseFactory $responseFactory
     */
    public function __construct(CurrentUserService $currentUserService, ResponseFactory $responseFactory)
    {
        $this->currentUserService = $currentUserService;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig_Error
     */
    public function __invoke(Request $request): Response
    {
        return $this->responseFactory->createTemplateResponse('team/list.html.twig', [
            'teams' => $this->currentUserService->getCurrentUser()->getTeams()
        ]);
    }
}
