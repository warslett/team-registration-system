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
     * @param Hike $hike
     * @return FormInterface
     */
    public function createForm(Hike $hike): FormInterface
    {
        return $this->formFactory->create(HikeDeleteType::class, $hike);
    }

    /**
     * @param FormInterface $form
     * @return Hike
     */
    public function processForm(FormInterface $form): Hike
    {
        $hike = $form->getData();
        $this->entityManager->remove($hike);
        $this->entityManager->flush();
        return $hike;
    }
}
