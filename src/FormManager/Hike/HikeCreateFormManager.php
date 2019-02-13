<?php

namespace App\FormManager\Hike;

use App\Entity\Event;
use App\Entity\Hike;
use App\Form\Hike\HikeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class HikeCreateFormManager
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
     * @param Event $event
     * @return FormInterface
     */
    public function createForm(Event $event): FormInterface
    {
        $hike = new Hike();
        $hike->setEvent($event);
        $hike->setFirstTeamStartTime($event->getDate());
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
        $this->entityManager->persist($hike);
        $this->entityManager->flush();
        return $hike;
    }
}
