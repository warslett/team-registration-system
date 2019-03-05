<?php


namespace App\Controller\Team;

use App\Entity\Team;
use App\Factory\ResponseFactory;
use App\Form\Team\TeamStartTimeType;
use App\Repository\TeamRepository;
use App\Service\NextTeamStartTimeService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class TeamAssignStartTimeController
{
    /**
     * @var TeamRepository
     */
    private $teamRepository;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var NextTeamStartTimeService
     */
    private $nextTeamStartTimeService;

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
     * @param TeamRepository $teamRepository
     * @param FlashBagInterface $flashBag
     * @param ResponseFactory $responseFactory
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $entityManager
     * @param NextTeamStartTimeService $nextTeamStartTimeService
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     * @param string $sender
     */
    public function __construct(
        TeamRepository $teamRepository,
        FlashBagInterface $flashBag,
        ResponseFactory $responseFactory,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        NextTeamStartTimeService $nextTeamStartTimeService,
        \Swift_Mailer $mailer,
        \Twig_Environment $twig,
        string $sender
    ) {
        $this->teamRepository = $teamRepository;
        $this->flashBag = $flashBag;
        $this->responseFactory = $responseFactory;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->nextTeamStartTimeService = $nextTeamStartTimeService;
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->sender = $sender;
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

        if ($team->getStartTime() !== null) {

            $this->flashBag->add('warning', sprintf(
                "Team \"%s\" already assigned start time",
                $team->getName()
            ));

            return $this->responseFactory->createRedirectResponse('hike_teams', [
                'hike_id' => $team->getHike()->getId()
            ]);
        }

        $team->setStartTime($this->nextTeamStartTimeService->getNextTeamStartTimeForHike($team->getHike()));

        $form = $this->formFactory->create(TeamStartTimeType::class, $team);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->entityManager->flush();

                $message = (new \Swift_Message())
                    ->setSubject("Payment Received for Three Towers")
                    ->setTo($team->getUser()->getEmail())
                    ->setFrom($this->sender)
                    ->setBody(
                        $this->twig->render(
                            'emails/start-time-assigned.html.twig',
                            ['team' => $team]
                        ),
                        'text/html'
                    )
                ;

                $this->mailer->send($message);

                $this->flashBag->add('success', sprintf(
                    "Team \"%s\" successfully updated for \"%s\"",
                    $team->getName(),
                    $team->getHike()->__toString()
                ));

                return $this->responseFactory->createRedirectResponse('hike_teams', [
                    'hike_id' => $team->getHike()->getId()
                ]);
            }

            $this->flashBag->add('danger', "There were some problems with the information you provided");
        }

        return $this->responseFactory->createTemplateResponse('admin/teams/assign-start-time.html.twig', [
            'team' => $team,
            'form' => $form->createView()
        ]);
    }
}
