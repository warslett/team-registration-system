<?php

namespace App\Controller\Team;

use App\Entity\Team;
use App\Repository\TeamRepository;
use App\Resolver\TeamResolver;
use App\Service\CurrentUserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TeamShowController
{

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var TeamResolver
     */
    private $teamResolver;

    /**
     * @param \Twig_Environment $twig
     * @param TeamResolver $teamResolver
     */
    public function __construct(
        \Twig_Environment $twig,
        TeamResolver $teamResolver
    ) {
        $this->twig = $twig;
        $this->teamResolver = $teamResolver;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig_Error
     */
    public function __invoke(Request $request)
    {
        $team = $this->teamResolver->resolveById($request->get('team_id'));
        return new Response($this->twig->render('team/show.html.twig', ['team' => $team]));
    }
}
