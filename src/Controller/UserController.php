<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController
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
     * UserController constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $entityManager
     * @param \Twig_Environment $twig
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        \Twig_Environment $twig
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->twig = $twig;
    }

    public function register(Request $request)
    {
        $user = new User();
        $form = $this->formFactory->create(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return new Response('success');
        }

        return new Response($this->twig->render(
            'user/register.html.twig',
            ['form' => $form->createView()]
        ));
    }
}