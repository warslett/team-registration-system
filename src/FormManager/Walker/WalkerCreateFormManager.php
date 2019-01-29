<?php

namespace App\FormManager\Walker;

use App\Entity\Team;
use App\Entity\Walker;
use App\Form\Walker\WalkerType;
use App\Service\WalkerReferenceCharacterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @todo Unit tests
 */
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
     * @param Team $team
     * @return FormInterface
     */
    public function createForm(Team $team): FormInterface
    {
        $walker = new Walker();
        $walker->setTeam($team);
        return $this->formFactory->create(WalkerType::class, $walker);
    }

    /**
     * @param FormInterface $form
     * @return Walker
     */
    public function processForm(FormInterface $form): Walker
    {
        /** @var Walker $walker */
        $walker = $form->getData();
        $walker->setReferenceCharacter(
            $this->walkerReferenceCharacterService->getNextAvailable($walker->getTeam())
        );
        $this->entityManager->persist($walker);
        $this->entityManager->flush();
        return $walker;
    }
}
