<?php

namespace App\FormManager\Team;

use App\Entity\Team;
use App\Form\Team\TeamUpdateType;
use App\Service\CurrentUserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class TeamUpdateFormManager
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
     * @param CurrentUserService $currentUserService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory
    ) {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

    /**
     * @param Team $team
     * @return FormInterface
     */
    public function createForm(Team $team): FormInterface
    {
        return $this->formFactory->create(TeamUpdateType::class, $team);
    }

    /**
     * @param FormInterface $form
     * @return Team
     */
    public function processForm(FormInterface $form): Team
    {
        /** @var Team $team */
        $team = $form->getData();
        $this->entityManager->flush();

        return $team;
    }
}
