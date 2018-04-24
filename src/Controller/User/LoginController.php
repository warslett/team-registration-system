<?php


namespace App\Controller\User;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController
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
     * LoginController constructor.
     * @param \Twig_Environment $twig
     * @param AuthenticationUtils $authenticationUtils
     */
    public function __construct(\Twig_Environment $twig, AuthenticationUtils $authenticationUtils)
    {
        $this->twig = $twig;
        $this->authenticationUtils = $authenticationUtils;
    }

    public function __invoke(Request $request): Response
    {
        $error = $this->authenticationUtils->getLastAuthenticationError();
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return new Response($this->twig->render('user/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        )));
    }
}
