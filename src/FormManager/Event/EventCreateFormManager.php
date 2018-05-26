<?php

namespace App\FormManager\Event;

use App\Entity\Event;
use App\Form\Event\EventCreateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class EventCreateFormManager
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
     * @return FormInterface
     */
    public function createForm(): FormInterface
    {
        return $this->formFactory->create(EventCreateType::class, new Event());
    }

    /**
     * @param FormInterface $form
     * @return Event
     */
    public function processForm(FormInterface $form): Event
    {
        /** @var Event $event */
        $event = $form->getData();
        $this->entityManager->persist($event);
        $this->entityManager->flush();
        return $event;
    }
}
