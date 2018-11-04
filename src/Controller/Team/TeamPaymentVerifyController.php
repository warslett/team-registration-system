<?php

namespace App\Controller\Team;

use App\Entity\TeamPayment;
use App\Factory\ResponseFactory;
use Payum\Core\Payum;
use Payum\Core\Request\GetHumanStatus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class TeamPaymentVerifyController
{

    /**
     * @var Payum
     */
    private $payum;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    public function __construct(Payum $payum, FlashBagInterface $flashBag, ResponseFactory $responseFactory)
    {
        $this->payum = $payum;
        $this->flashBag = $flashBag;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function __invoke(Request $request)
    {
        $verifier = $this->payum->getHttpRequestVerifier();
        $token = $verifier->verify($request);
        $gateway = $this->payum->getGateway($token->getGatewayName());

        $status = new GetHumanStatus($token);
        $gateway->execute($status);

        /** @var TeamPayment $payment */
        $payment = $status->getFirstModel();

        if ($status->isCaptured() || $status->isAuthorized()) {
            $payment->setIsCompleted(true);
            $this->payum->getStorage(TeamPayment::class)->update($payment);
            $this->flashBag->add('success', "Your payment was successful");
        } else {
            $this->flashBag->add('danger', "There was a problem with your payment");
        }

        $verifier->invalidate($token);

        return $this->responseFactory->createRedirectResponse('team_show', [
            'team_id' => $payment->getTeam()->getId()
        ]);
    }
}
