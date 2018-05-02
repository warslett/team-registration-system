<?php

namespace App\Controller\User;

use App\FormManager\User\UserRegisterFormManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

class UserRegisterController
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
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var UserRegisterFormManager
     */
    private $formManager;

    /**
     * @param \Twig_Environment $twig
     * @param RouterInterface $router
     * @param FlashBagInterface $flashBag
     * @param UserRegisterFormManager $formManager
     */
    public function __construct(
        \Twig_Environment $twig,
        RouterInterface $router,
        FlashBagInterface $flashBag,
        UserRegisterFormManager $formManager
    ) {
        $this->twig = $twig;
        $this->router = $router;
        $this->flashBag = $flashBag;
        $this->formManager = $formManager;
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
        $form = $this->formManager->createForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->formManager->processForm($form);
                $this->flashBag->add('success', "Registration successful");

                return new RedirectResponse($this->router->generate('user_login'));
            }

            $this->flashBag->add('danger', "There were some problems with the information you provided");
        }

        return new Response($this->twig->render(
            'user/register.html.twig',
            ['form' => $form->createView()]
        ));
    }
}
