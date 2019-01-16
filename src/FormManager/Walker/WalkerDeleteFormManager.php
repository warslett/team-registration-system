<?php

namespace App\FormManager\Walker;

use App\Entity\Walker;
use App\Form\Walker\WalkerDeleteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class WalkerDeleteFormManager
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
     * WalkerDeleteFormManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    /**
     * @param Walker $walker
     * @return FormInterface
     */
    public function createForm(Walker $walker): FormInterface
    {
        return $this->formFactory->create(WalkerDeleteType::class, $walker);
    }

    /**
     * @param FormInterface $form
     * @return Walker
     */
    public function processForm(FormInterface $form): Walker
    {
        $walker = $form->getData();
        $this->entityManager->remove($walker);
        $this->entityManager->flush();
        return $walker;
    }
}
