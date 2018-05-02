<?php

namespace App\Controller\Team;

use App\Factory\ResponseFactory;
use App\Resolver\TeamResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamShowController
{

    /**
     * @var TeamResolver
     */
    private $teamResolver;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @param TeamResolver $teamResolver
     * @param ResponseFactory $responseFactory
     */
    public function __construct(
        TeamResolver $teamResolver,
        ResponseFactory $responseFactory
    ) {
        $this->teamResolver = $teamResolver;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig_Error
     */
    public function __invoke(Request $request)
    {
        $team = $this->teamResolver->resolveById($request->get('team_id'));
        return $this->responseFactory->createTemplateResponse('team/show.html.twig', ['team' => $team]);
    }
}
