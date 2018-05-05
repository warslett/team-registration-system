<?php

namespace App\FormManager\Walker;

use App\Entity\Team;
use App\Entity\Walker;
use App\Form\Walker\WalkerCreateType;
use App\Service\WalkerReferenceCharacterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class WalkerCreateFormManager
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
     * @var WalkerReferenceCharacterService
     */
    private $walkerReferenceCharacterService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param FormFactoryInterface $formFactory
     * @param WalkerReferenceCharacterService $walkerReferenceCharacterService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory,
        WalkerReferenceCharacterService $walkerReferenceCharacterService
    ) {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->walkerReferenceCharacterService = $walkerReferenceCharacterService;
    }

    /**
     * @return FormInterface
     */
    public function createForm(): FormInterface
    {
        return $this->formFactory->create(WalkerCreateType::class, new Walker());
    }

    /**
     * @param Team $team
     * @param FormInterface $form
     * @return Walker
     */
    public function processForm(Team $team, FormInterface $form): Walker
    {
        /** @var Walker $walker */
        $walker = $form->getData();
        $walker->setTeam($team);
        $walker->setReferenceCharacter(
            $this->walkerReferenceCharacterService->getNextAvailable($team)
        );
        $this->entityManager->persist($walker);
        $this->entityManager->flush();
        return $walker;
    }
}
