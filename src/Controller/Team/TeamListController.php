<?php


namespace App\Controller\Team;

use App\Service\CurrentUserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamListController
{

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var CurrentUserService
     */
    private $currentUserService;

    /**
     * TeamController constructor.
     * @param \Twig_Environment $twig
     * @param CurrentUserService $currentUserService
     */
    public function __construct(\Twig_Environment $twig, CurrentUserService $currentUserService)
    {
        $this->twig = $twig;
        $this->currentUserService = $currentUserService;
    }

    public function __invoke(Request $request): Response
    {
        return new Response($this->twig->render('team/list.html.twig', [
            'teams' => $this->currentUserService->getCurrentUser()->getTeams()
        ]));
    }
}
