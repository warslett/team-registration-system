<?php

namespace App\Controller\Team;

use App\Resolver\TeamResolver;
use App\Service\TeamPaymentCaptureService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamPaymentCaptureController
{

    /**
     * @var TeamResolver
     */
    private $teamResolver;

    /**
     * @var TeamPaymentCaptureService
     */
    private $paymentCaptureService;

    public function __construct(TeamResolver $teamResolver, TeamPaymentCaptureService $paymentCaptureService)
    {
        $this->teamResolver = $teamResolver;
        $this->paymentCaptureService = $paymentCaptureService;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $team = $this->teamResolver->resolveById($request->get('team_id'));
        $captureToken = $this->paymentCaptureService->generatePaymentCaptureTokenForTeam($team);
        return new RedirectResponse($captureToken->getTargetUrl());
    }
}
