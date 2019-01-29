<?php


namespace App\FormManager\Hike;

use App\Entity\Hike;
use App\Form\Hike\HikeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class HikeUpdateFormManager
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
     * @param Hike $hike
     * @return FormInterface
     */
    public function createForm(Hike $hike): FormInterface
    {
        return $this->formFactory->create(HikeType::class, $hike);
    }

    /**
     * @param FormInterface $form
     * @return Hike
     */
    public function processForm(FormInterface $form): Hike
    {
        /** @var Hike $hike */
        $hike = $form->getData();
        $this->entityManager->flush();

        return $hike;
    }
}
