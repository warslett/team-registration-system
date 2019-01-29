<?php


namespace App\FormManager\Event;

use App\Entity\Event;
use App\Form\Event\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class EventUpdateFormManager
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
     * @param Event $event
     * @return FormInterface
     */
    public function createForm(Event $event): FormInterface
    {
        return $this->formFactory->create(EventType::class, $event);
    }

    /**
     * @param FormInterface $form
     * @return Event
     */
    public function processForm(FormInterface $form): Event
    {
        /** @var Event $event */
        $event = $form->getData();
        $this->entityManager->flush();

        return $event;
    }
}
