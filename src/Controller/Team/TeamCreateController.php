<?php


namespace App\Controller\Team;

use App\Entity\Team;
use App\Form\Team\TeamCreateType;
use App\Service\CurrentUserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

class TeamCreateController
{

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var CurrentUserService
     */
    private $currentUserService;

    /**
     * @param \Twig_Environment $twig
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $entityManager
     * @param CurrentUserService $currentUserService
     * @param RouterInterface $router
     * @param FlashBagInterface $flashBag
     */
    public function __construct(
        \Twig_Environment $twig,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        CurrentUserService $currentUserService,
        RouterInterface $router,
        FlashBagInterface $flashBag
    ) {
        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->flashBag = $flashBag;
        $this->currentUserService = $currentUserService;
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(Request $request)
    {
        $team = new Team();
        $form = $this->formFactory->create(TeamCreateType::class, $team);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $team->setUser($this->currentUserService->getCurrentUser());

                $this->entityManager->persist($team);
                $this->entityManager->flush();

                $this->flashBag->add('success', sprintf(
                    "Team \"%s\" successfully created for \"%s\"",
                    $team->getName(),
                    $team->getHike()->__toString()
                ));

                return new RedirectResponse($this->router->generate('team_show', ['team_id' => $team->getId()]));
            }

            $this->flashBag->add('danger', "There were some problems with the information you provided");
        }

        return new Response($this->twig->render('team/create.html.twig', [
            'form' => $form->createView()
        ]));
    }
}
