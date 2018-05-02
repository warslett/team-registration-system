<?php

namespace App\Controller\User;

use App\Factory\ResponseFactory;
use App\FormManager\User\UserRegisterFormManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class UserRegisterController
{

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var UserRegisterFormManager
     */
    private $userRegisterFormManager;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @param FlashBagInterface $flashBag
     * @param UserRegisterFormManager $userRegisterFormManager
     * @param ResponseFactory $responseFactory
     */
    public function __construct(
        FlashBagInterface $flashBag,
        UserRegisterFormManager $userRegisterFormManager,
        ResponseFactory $responseFactory
    ) {
        $this->flashBag = $flashBag;
        $this->userRegisterFormManager = $userRegisterFormManager;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig_Error
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->userRegisterFormManager->createForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user = $this->userRegisterFormManager->processForm($form);
                $this->flashBag->add('success', sprintf("Successfully registered %s", $user->getEmail()));

                return $this->responseFactory->createRedirectResponse('user_login');
            }

            $this->flashBag->add('danger', "There were some problems with the information you provided");
        }

        return $this->responseFactory->createTemplateResponse(
            'user/register.html.twig',
            ['form' => $form->createView()]
        );
    }
}
