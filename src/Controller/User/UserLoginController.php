<?php

namespace App\Controller\User;

use App\Factory\ResponseFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
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
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @param AuthenticationUtils $authenticationUtils
     * @param ResponseFactory $responseFactory
     * @param FlashBagInterface $flashBag
     */
    public function __construct(
        AuthenticationUtils $authenticationUtils,
        ResponseFactory $responseFactory,
        FlashBagInterface $flashBag
    ) {
        $this->authenticationUtils = $authenticationUtils;
        $this->responseFactory = $responseFactory;
        $this->flashBag = $flashBag;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig_Error
     */
    public function __invoke(Request $request): Response
    {
        $error = $this->authenticationUtils->getLastAuthenticationError();
        if (!is_null($error)) {
            $this->flashBag->add('danger', $error->getMessageKey());
        }
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return $this->responseFactory->createTemplateResponse('user/login.html.twig', [
            'last_username' => $lastUsername,
        ]);
    }
}
