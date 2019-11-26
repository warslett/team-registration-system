<?php

namespace App\Controller\User;

use App\Exception\ResetPasswordTokenInvalidException;
use App\Form\User\ResetPasswordType;
use App\Service\ResetPasswordTokenService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Twig\Environment;

class ResetPasswordController
{

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var ResetPasswordTokenService
     */
    private $resetPasswordTokenService;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param FlashBagInterface $flashBag
     * @param Environment $twig
     * @param FormFactoryInterface $formFactory
     * @param ResetPasswordTokenService $resetPasswordTokenService
     * @param RouterInterface $router
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        FlashBagInterface $flashBag,
        Environment $twig,
        FormFactoryInterface $formFactory,
        ResetPasswordTokenService $resetPasswordTokenService,
        RouterInterface $router,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager
    ) {
        $this->flashBag = $flashBag;
        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->resetPasswordTokenService = $resetPasswordTokenService;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig_Error
     */
    public function __invoke(Request $request): Response
    {
        try {
            $resetPasswordToken = $request->get('rtoken');
            if (is_null($resetPasswordToken)) {
                throw new ResetPasswordTokenInvalidException("no rtoken in querystring");
            }

            $user = $this->resetPasswordTokenService->resolveUserFromToken($resetPasswordToken);
            $form = $this->formFactory->create(ResetPasswordType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                if ($form->isValid()) {
                    $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
                    $user->setPassword($password);

                    $this->resetPasswordTokenService->inValidateResetPasswordToken($user);
                    $this->entityManager->flush();
                    $this->flashBag->add('success', sprintf("Successfully reset password for %s", $user->getEmail()));

                    return new RedirectResponse($this->router->generate('user_login'));
                }

                $this->flashBag->add('danger', "There were some problems with the information you provided");
            }

            return new Response($this->twig->render('user/password/reset.html.twig', [
                'user' => $user,
                'form' => $form->createView()
            ]));
        } catch (ResetPasswordTokenInvalidException $e) {
            $this->flashBag->add('danger', 'Your reset link has expired or is invalid');

            return new RedirectResponse($this->router->generate('user_login'));
        }
    }
}
