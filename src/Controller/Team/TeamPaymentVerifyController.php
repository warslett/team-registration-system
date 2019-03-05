<?php

namespace App\Controller\Team;

use App\Entity\TeamPayment;
use App\Factory\ResponseFactory;
use App\Service\NextTeamStartTimeService;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * @var NextTeamStartTimeService
     */
    private $nextTeamStartTimeService;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var string
     */
    private $sender;

    /**
     * @param Payum $payum
     * @param FlashBagInterface $flashBag
     * @param ResponseFactory $responseFactory
     * @param NextTeamStartTimeService $nextTeamStartTimeService
     * @param EntityManagerInterface $entityManager
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     * @param string $sender
     */
    public function __construct(
        Payum $payum,
        FlashBagInterface $flashBag,
        ResponseFactory $responseFactory,
        NextTeamStartTimeService $nextTeamStartTimeService,
        EntityManagerInterface $entityManager,
        \Swift_Mailer $mailer,
        \Twig_Environment $twig,
        string $sender
    ) {
        $this->payum = $payum;
        $this->flashBag = $flashBag;
        $this->responseFactory = $responseFactory;
        $this->nextTeamStartTimeService = $nextTeamStartTimeService;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->sender = $sender;
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

            $message = (new \Swift_Message())
                ->setSubject("Payment Received for Three Towers")
                ->setTo($payment->getTeam()->getUser()->getEmail())
                ->setFrom($this->sender)
                ->setBody(
                    $this->twig->render(
                        'emails/payment-received.html.twig',
                        ['payment' => $payment]
                    ),
                    'text/html'
                )
            ;

            $this->mailer->send($message);

            $this->flashBag->add('success', "Your payment was successful, we have emailed you a receipt");
        } else {
            $this->flashBag->add('danger', "There was a problem with your payment");
        }

        $verifier->invalidate($token);

        return $this->responseFactory->createRedirectResponse('team_show', [
            'team_id' => $payment->getTeam()->getId()
        ]);
    }
}
