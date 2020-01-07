<?php

namespace App\FormManager\Team;

use App\Entity\Team;
use App\Entity\TeamPayment;
use App\Form\Team\TeamPaymentCreateOfflineType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class TeamPaymentCreateOfflineFormManager
{

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(FormFactoryInterface $formFactory, EntityManagerInterface $entityManager)
    {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Team $team
     * @return FormInterface
     */
    public function createForm(Team $team): FormInterface
    {
        $teamPayment = new TeamPayment();
        $teamPayment->setTeam($team);
        $teamPayment->setDetails(['TIMESTAMP' => date('c')]);
        return $this->formFactory->create(TeamPaymentCreateOfflineType::class, $teamPayment);
    }

    public function processForm(FormInterface $form): TeamPayment
    {
        /** @var TeamPayment $teamPayment */
        $teamPayment = $form->getData();
        $teamPayment->setCurrencyCode('GBP');
        $teamPayment->setIsCompleted(true);

        $this->entityManager->persist($teamPayment);
        $this->entityManager->flush();

        return $teamPayment;
    }
}
