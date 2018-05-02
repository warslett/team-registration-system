<?php


namespace App\Controller\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserLoginController
{

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtils;

    /**
     * @param \Twig_Environment $twig
     * @param AuthenticationUtils $authenticationUtils
     */
    public function __construct(\Twig_Environment $twig, AuthenticationUtils $authenticationUtils)
    {
        $this->twig = $twig;
        $this->authenticationUtils = $authenticationUtils;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(Request $request): Response
    {
        $error = $this->authenticationUtils->getLastAuthenticationError();
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return new Response($this->twig->render('user/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]));
    }
}
