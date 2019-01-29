<?php

namespace App\Controller\User;

use App\Factory\ResponseFactory;
use App\FormManager\User\UserUpdateFormManager;
use App\Service\CurrentUserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class UserUpdateController
{

    /**
     * @var CurrentUserService
     */
    private $currentUserService;

    /**
     * @var UserUpdateFormManager
     */
    private $userUpdateFormManager;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    public function __construct(
        CurrentUserService $currentUserService,
        UserUpdateFormManager $userUpdateFormManager,
        FlashBagInterface $flashBag,
        ResponseFactory $responseFactory
    ) {
        $this->currentUserService = $currentUserService;
        $this->userUpdateFormManager = $userUpdateFormManager;
        $this->flashBag = $flashBag;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig_Error
     */
    public function __invoke(Request $request): Response
    {
        $user = $this->currentUserService->getCurrentUser();
        $form = $this->userUpdateFormManager->createForm($user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user = $this->userUpdateFormManager->processForm($form);

                $this->flashBag->add(
                    'success',
                    sprintf("User account successfully updated for %s", $user->getFullName())
                );

                return $this->responseFactory->createRedirectResponse('user_update');
            }

            $this->flashBag->add('danger', "There were some problems with the information you provided");
        }

        return $this->responseFactory->createTemplateResponse('user/update.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
