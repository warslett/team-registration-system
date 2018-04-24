<?php


namespace App\Controller\User;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

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
     * UserController constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $entityManager
     * @param \Twig_Environment $twig
     * @param RouterInterface $router
     * @param FlashBagInterface $flashBag
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        \Twig_Environment $twig,
        RouterInterface $router,
        FlashBagInterface $flashBag
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->twig = $twig;
        $this->router = $router;
        $this->flashBag = $flashBag;
    }

    public function __invoke(Request $request): Response
    {
        $user = new User();
        $form = $this->formFactory->create(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

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
