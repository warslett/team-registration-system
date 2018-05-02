<?php

namespace App\Controller\User;

use App\Factory\ResponseFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserLoginController
{

    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtils;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @param AuthenticationUtils $authenticationUtils
     * @param ResponseFactory $responseFactory
     */
    public function __construct(AuthenticationUtils $authenticationUtils, ResponseFactory $responseFactory)
    {
        $this->authenticationUtils = $authenticationUtils;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig_Error
     */
    public function __invoke(Request $request): Response
    {
        $error = $this->authenticationUtils->getLastAuthenticationError();
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return $this->responseFactory->createTemplateResponse('user/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }
}
