<?php

namespace App\Controller;

use App\Factory\ResponseFactory;
use App\Service\CurrentUserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController
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

    public function __invoke(Request $request): Response
    {
        if (is_null($this->currentUserService->getCurrentUser())) {
            return $this->responseFactory->createRedirectResponse('user_login');
        }
        return $this->responseFactory->createRedirectResponse('team_list');
    }
}
