<?php

namespace App\FormManager\User;

use App\Entity\Team;
use App\Entity\User;
use App\Form\User\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class UserUpdateFormManager
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @param EntityManagerInterface $entityManager
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory
    ) {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    /**
     * @param User $user
     * @return FormInterface
     */
    public function createForm(User $user): FormInterface
    {
        return $this->formFactory->create(UserType::class, $user);
    }

    /**
     * @param FormInterface $form
     * @return Team
     */
    public function processForm(FormInterface $form): User
    {
        /** @var User $user */
        $user = $form->getData();
        $this->entityManager->flush();

        return $user;
    }
}
