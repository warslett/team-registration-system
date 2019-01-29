<?php

namespace App\FormManager\Hike;

use App\Entity\Hike;
use App\Form\Hike\HikeDeleteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class HikeDeleteFormManager
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
    public function __construct(EntityManagerInterface $entityManager, FormFactoryInterface $formFactory)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    /**
     * @param Hike $hiker
     * @return FormInterface
     */
    public function createForm(Hike $hiker): FormInterface
    {
        return $this->formFactory->create(HikeDeleteType::class, $hiker);
    }

    /**
     * @param FormInterface $form
     * @return Hike
     */
    public function processForm(FormInterface $form): Hike
    {
        $hiker = $form->getData();
        $this->entityManager->remove($hiker);
        $this->entityManager->flush();
        return $hiker;
    }
}
