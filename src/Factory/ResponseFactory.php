<?php

namespace App\Factory;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class ResponseFactory
{

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param \Twig_Environment $twig
     * @param RouterInterface $router
     */
    public function __construct(\Twig_Environment $twig, RouterInterface $router)
    {
        $this->twig = $twig;
        $this->router = $router;
    }

    /**
     * @param string $template
     * @param array $context
     * @return Response
     * @throws \Twig_Error
     */
    public function createTemplateResponse(string $template, array $context = []): Response
    {
        $response = new Response();
        $response->setContent($this->twig->render($template, $context));
        return $response;
    }

    /**
     * @param string $routeName
     * @param array $routeParameters
     * @return RedirectResponse
     */
    public function createRedirectResponse(string $routeName, array $routeParameters = []): RedirectResponse
    {
        return new RedirectResponse($this->router->generate($routeName, $routeParameters));
    }
}
