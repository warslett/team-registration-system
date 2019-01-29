<?php

namespace App\FormManager\Event;

use App\Entity\Event;
use App\Form\Event\EventDeleteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class EventDeleteFormManager
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
        return $this->formFactory->create(EventDeleteType::class, $event);
    }

    /**
     * @param FormInterface $form
     * @return Event
     */
    public function processForm(FormInterface $form): Event
    {
        $event = $form->getData();
        $this->entityManager->remove($event);
        $this->entityManager->flush();
        return $event;
    }
}
