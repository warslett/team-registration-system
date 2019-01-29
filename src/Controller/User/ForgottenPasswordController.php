<?php


namespace App\Controller\User;


use App\Exception\PasswordResetLimitReachedException;
use App\Form\User\ForgottenPasswordType;
use App\Repository\UserRepository;
use App\Service\ResetPasswordTokenService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class ForgottenPasswordController
{

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var ResetPasswordTokenService
     */
    private $resetPasswordTokenService;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        FormFactoryInterface $formFactory,
        Environment $twig,
        UserRepository $userRepository,
        FlashBagInterface $flashBag,
        RouterInterface $router,
        ResetPasswordTokenService $resetPasswordTokenService,
        EntityManagerInterface $entityManager
    ) {
        $this->formFactory = $formFactory;
        $this->twig = $twig;
        $this->userRepository = $userRepository;
        $this->flashBag = $flashBag;
        $this->router = $router;
        $this->resetPasswordTokenService = $resetPasswordTokenService;
        $this->entityManager = $entityManager;
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
        $form = $this->formFactory->create(ForgottenPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $this->userRepository->findOneByEmail($email);

            if (is_null($user)) {
                $this->flashBag->set('danger', sprintf("There is no user with the address %s", $email));
            }

            try {
                $this->resetPasswordTokenService->triggerResetPassword($user, new \DateTime('+24 hours'));
                $this->flashBag->set('success', sprintf("An email has been sent to %s to reset your password", $email));
            } catch (PasswordResetLimitReachedException $e) {
                $this->flashBag->set('danger', $e->getMessage());
            }
            $this->entityManager->flush();
            return new RedirectResponse($this->router->generate('user_login'));
        }

        return new Response($this->twig->render('user/password/forgotten-password.html.twig', [
            'form' => $form->createView()
        ]));
    }
}