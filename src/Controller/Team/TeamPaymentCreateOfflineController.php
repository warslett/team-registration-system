<?php


namespace App\Controller\Team;

use App\Factory\ResponseFactory;
use App\FormManager\Team\TeamPaymentCreateOfflineFormManager;
use App\Repository\TeamRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TeamPaymentCreateOfflineController
{

    /**
     * @var TeamRepository
     */
    private $teamRepository;

    /**
     * @var TeamPaymentCreateOfflineFormManager
     */
    private $teamPaymentCreateOfflineFormManager;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(
        TeamRepository $teamRepository,
        TeamPaymentCreateOfflineFormManager $teamPaymentCreateOfflineFormManager,
        ResponseFactory $responseFactory,
        FlashBagInterface $flashBag
    ) {
        $this->teamRepository = $teamRepository;
        $this->teamPaymentCreateOfflineFormManager = $teamPaymentCreateOfflineFormManager;
        $this->responseFactory = $responseFactory;
        $this->flashBag = $flashBag;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig_Error
     */
    public function __invoke(Request $request): Response
    {
        $teamId = $request->get('team_id');
        $team = $this->teamRepository->find($teamId);

        if (is_null($team)) {
            throw new NotFoundHttpException(sprintf("No team found with the id %d", $teamId));
        }

        $form = $this->teamPaymentCreateOfflineFormManager->createForm($team);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $teamPayment = $this->teamPaymentCreateOfflineFormManager->processForm($form);

                $this->flashBag->add('success', sprintf(
                    "Successfully registered payment of %s for Team \"%s\"",
                    "£" . strval($teamPayment->getTotalAmount()/100),
                    $teamPayment->getTeam()->getName()
                ));

                return $this->responseFactory->createRedirectResponse('hike_teams', [
                    'hike_id' => $team->getHike()->getId()
                ]);
            }

            $this->flashBag->add('danger', "There were some problems with the information you provided");
        }

        return $this->responseFactory->createTemplateResponse('admin/teams/create-offline.html.twig', [
            'team' => $team,
            'form' => $form->createView()
        ]);
    }
}
