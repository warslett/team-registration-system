<?php

namespace App\FormManager\Team;

use App\Entity\Team;
use App\Form\Team\TeamCreateType;
use App\Service\CurrentUserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class TeamCreateFormManager
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
     * @var CurrentUserService
     */
    private $currentUserService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param FormFactoryInterface $formFactory
     * @param CurrentUserService $currentUserService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory,
        CurrentUserService $currentUserService
    ) {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->currentUserService = $currentUserService;
    }

    /**
     * @return FormInterface
     */
    public function createForm(): FormInterface
    {
        return $this->formFactory->create(TeamCreateType::class, new Team());
    }

    /**
     * @param FormInterface $form
     * @return Team
     */
    public function processForm(FormInterface $form): Team
    {
        /** @var Team $team */
        $team = $form->getData();
        $team->setUser($this->currentUserService->getCurrentUser());

        $this->entityManager->persist($team);
        $this->entityManager->flush();

        return $team;
    }
}
