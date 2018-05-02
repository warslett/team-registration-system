<?php


namespace App\Controller\Team;

use App\Entity\Team;
use App\Form\Team\TeamCreateType;
use App\FormManager\Team\TeamCreateFormManager;
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
     * @var RouterInterface
     */
    private $router;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var TeamCreateFormManager
     */
    private $userCreateFormManager;

    /**
     * @param \Twig_Environment $twig
     * @param RouterInterface $router
     * @param FlashBagInterface $flashBag
     * @param TeamCreateFormManager $userCreateFormManager
     */
    public function __construct(
        \Twig_Environment $twig,
        RouterInterface $router,
        FlashBagInterface $flashBag,
        TeamCreateFormManager $userCreateFormManager
    ) {
        $this->twig = $twig;
        $this->router = $router;
        $this->flashBag = $flashBag;
        $this->userCreateFormManager = $userCreateFormManager;
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig_Error
     */
    public function __invoke(Request $request)
    {
        $form = $this->userCreateFormManager->createForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $team = $this->userCreateFormManager->processForm($form);

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
