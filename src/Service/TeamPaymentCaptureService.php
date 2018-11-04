<?php

namespace App\Service;

use App\Entity\Team;
use App\Entity\TeamPayment;
use Payum\Core\Payum;
use Payum\Core\Security\TokenInterface;

class TeamPaymentCaptureService
{

    /**
     * @var Payum
     */
    private $payum;

    /**
     * @var CurrentUserService
     */
    private $currentUserService;

    public function __construct(Payum $payum, CurrentUserService $currentUserService)
    {
        $this->payum = $payum;
        $this->currentUserService = $currentUserService;
    }

    /**
     * @param Team $team
     * @return TokenInterface
     */
    public function generatePaymentCaptureTokenForTeam(Team $team): TokenInterface
    {
        $storage = $this->payum->getStorage(TeamPayment::class);
        $currentUser = $this->currentUserService->getCurrentUser();

        /** @var TeamPayment $payment */
        $payment = $storage->create();
        $payment->setNumber(uniqid());
        $payment->setCurrencyCode('GBP');
        $payment->setTotalAmount($team->getFeesDue()*100);
        $payment->setDescription($team->getPaymentDescription());
        $payment->setClientId($currentUser->getId());
        $payment->setClientEmail($currentUser->getEmail());
        $payment->setTeam($team);

        $storage->update($payment);

        return $this->payum->getTokenFactory()->createCaptureToken(
            'paypal',
            $payment,
            'team_payment_verify',
            ['team_id' => $team->getId()]
        );
    }
}