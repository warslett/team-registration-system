<?php


namespace App\Controller\Hike;

use App\Factory\ResponseFactory;
use App\Resolver\HikeResolver;
use App\Service\CurrentUserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HikeTeamsController
{

    /**
     * @var HikeResolver
     */
    private $hikeResolver;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @param HikeResolver $hikeResolver
     * @param ResponseFactory $responseFactory
     */
    public function __construct(HikeResolver $hikeResolver, ResponseFactory $responseFactory)
    {
        $this->hikeResolver = $hikeResolver;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig_Error
     */
    public function __invoke(Request $request): Response
    {
        $hike = $this->hikeResolver->resolveById($request->get('hike_id'));
        return $this->responseFactory->createTemplateResponse('hikes/teams.html.twig', [
            'hike' => $hike,
            'teams' => $hike->getTeams()
        ]);
    }
}
