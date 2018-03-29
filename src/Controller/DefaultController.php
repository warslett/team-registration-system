<?php

namespace App\Controller;

use App\Service\CurrentUserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class DefaultController
{

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var CurrentUserService
     */
    private $currentUserService;

    /**
     * DefaultController constructor.
     * @param RouterInterface $router
     * @param CurrentUserService $currentUserService
     */
    public function __construct(RouterInterface $router, CurrentUserService $currentUserService)
    {
        $this->router = $router;
        $this->currentUserService = $currentUserService;
    }

    public function __invoke(Request $request): Response
    {
        if (is_null($this->currentUserService->getCurrentUser())) {
            return new RedirectResponse($this->router->generate('user_login'));
        }
        return new RedirectResponse($this->router->generate('team_list'));
    }
}
