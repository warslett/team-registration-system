<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamController
{

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * TeamController constructor.
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function list(Request $request): Response
    {
        return new Response($this->twig->render('team/list.html.twig'));
    }
}